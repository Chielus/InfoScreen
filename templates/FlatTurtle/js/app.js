(function($) {

	$(document).ready(function(){new App($("#app"), LiveBoardConfig);});

	var App = function(rootElement, config) {
		var rootElement = rootElement;
		var that = this;

		this.messageOfTheDay;
		
		this.clock;
		this.nmbsSystemPane;
		this.mivbSystemPane;

		var initialize = function() {
			this.companyLogo = config.companyLogo;
			this.messageOfTheDay = config.messageOfTheDay;

			initializeHtml();
			addBehaviours();
			
			irail.set_default_lang("en");
			
			this.clock = new Clock(rootElement.find(".Clock"));
			this.nmbsSystemPane = new SystemPane(rootElement.find(".SystemPane.nmbs"), "nmbs", config);
			this.mivbSystemPane = new SystemPane(rootElement.find(".SystemPane.mivb"), "mivb", config);
		};

		var initializeHtml = function() {
			rootElement = rootElement.templateReplace("App", that);
		};

		var addBehaviours = function() {
		};

		var removeBehaviours = function() {
		};

		this.destroy = function() {
			removeBehaviours();

			this.nmbsSystemPane.destroy();
			this.mivbSystemPane.destroy();
			
			rootElement.empty();
		}

		this.update = function() {
			this.destroy();
			initialize();
		}

		initialize.apply(this, arguments);
	};
	
	var Clock = function(rootElement) {
		var rootElement = rootElement;
		var interval;
		var that = this;
		
		var refresh = function() {
			rootElement = rootElement.templateReplace("Clock", that);
		};
		
		this.time = function() {
			var now = new Date(),
			    hours = now.getHours(),
			    minutes = now.getMinutes();
			return (hours<10?'0':'')+hours+':'+(minutes<10?'0':'')+minutes;
		};
		
		var initialize = function() {
			initializeHtml();
			addBehaviours();
		};

		var initializeHtml = function() {
			rootElement = rootElement.templateReplace("Clock", that);
		};

		var addBehaviours = function() {
			interval = window.setInterval(refresh, 500);
		};

		var removeBehaviours = function() {
			window.clearInterval(interval);
		};

		this.destroy = function() {
			removeBehaviours();

			rootElement.empty();
		}

		this.update = function() {
			this.destroy();
			initialize();
		}
		
		initialize.apply(this, arguments);
	};
	
	var SystemPane = function(rootElement, system, config) {
		var rootElement = rootElement;
		var liveBoardContainer;
		var liveBoardsTicker;
		var currentLiveBoardIndex;
		var interval;
		var that = this;
		var liveBoards = [];
		
		this.name;
		
		var updateTicker = function() {
			liveBoardsTicker = liveBoardsTicker.templateReplace("LiveBoardsTicker", {currentLiveBoardIndex:currentLiveBoardIndex, liveBoardsCount:liveBoards.length});
		};
		
		var initialize = function() {
			currentLiveBoardIndex = 0;
			liveBoards = [];

			this.name = system;
			
			initializeHtml();
			addBehaviours();
			
			$.each(config.liveboards[system], function(i, station) {
				liveBoards.push(new LiveBoard(liveBoardContainer.templateAppend("EmptyDiv"), system, station, config).stop().hide());
			});
			
			liveBoards[currentLiveBoardIndex] && liveBoards[currentLiveBoardIndex].start().show();
			
			updateTicker();
		};

		var initializeHtml = function() {
			rootElement = rootElement.templateReplace("SystemPane", that);
			liveBoardsTicker = rootElement.find(".liveboardsTicker");
			liveBoardContainer = rootElement.find(".liveboards");
		};

		var addBehaviours = function() {
			interval = window.setInterval(function() {
				var previousIndex = currentLiveBoardIndex;
				var nextIndex = (currentLiveBoardIndex+1)%liveBoards.length;
				liveBoards[previousIndex].stop();
				liveBoards[nextIndex].start(function() {
					currentLiveBoardIndex = nextIndex;
					liveBoards[previousIndex].hide();
					liveBoards[nextIndex].show();
					updateTicker();
				});
			}, config.cycleLiveboardsInterval*1000);
		};

		var removeBehaviours = function() {
			window.clearInterval(interval);
		};

		this.destroy = function() {
			removeBehaviours();

			$.each(liveBoards, function(i, liveBoard) {
				liveBoard.destroy();
			});

			rootElement.empty();
		}

		this.update = function() {
			this.destroy();
			initialize();
		}
		
		initialize.apply(this, arguments);
	};

	var LiveBoard = function(rootElement, system, station, config) {
		var rootElement = rootElement;
		var interval;
		var rows;
		var rowContainer;
		var paused = true;
		var that = this;
		var refreshCallback;
		
		this.name;
		this.distanceMeters;
		this.distanceWalking;
		
		this.start = function(callback) {
			refreshCallback = callback;
			startRefresh();
			return this;
		};
		this.stop = function() {
			stopRefresh();
			return this;
		};
		this.show = function() {
			rootElement.show();
			return this;
		};
		this.hide = function() {
			rootElement.hide();
			return this;
		};
		
		var refresh = function() {
			irail.liveboards.lookup(system, station.name, "DEP", function(data) {
				$.each(rows, function(i, entry) {
					entry.destroy(true);
				});
				for(var i=0; i<data.entries.length&&i<config.rowsToShow; i++) {
					rows.push(new LiveBoardRow(rowContainer.templateAppend("EmptyTr"), system, data.entries[i]));
				}
				refreshCallback && refreshCallback();
				refreshCallback = null;
			});
		};
		var startRefresh = function() {
			refresh();
			!interval && (interval = window.setInterval(refresh, config.refreshLiveboardsInterval*1000));
		};

		var stopRefresh = function() {
			interval && window.clearInterval(interval);
			interval = false;
		};
		
		var initialize = function() {
			this.name = station.name;
			this.distanceMeters = station.distanceMeters;
			this.distanceWalking = station.distanceWalking;
			rows = [];
			initializeHtml();
			addBehaviours();			
		};

		var initializeHtml = function() {
			rootElement = rootElement.templateReplace("LiveBoard", that);
			rowContainer = rootElement.find("table tbody");
		};

		var addBehaviours = function() {
		};

		var removeBehaviours = function() {
			stopRefresh();
		};

		this.destroy = function(remove) {
			removeBehaviours();

			$.each(rows, function(i, entry) {
				entry.destroy(true);
			});

			rootElement.empty();
			if (remove) {
				rootElement.remove();
				delete this;
			}
		}

		this.update = function() {
			that.destroy();
			initialize();
		}
		
		initialize.apply(this, arguments);
	};

	var LiveBoardRow = function(rootElement, system, data) {
		var rootElement = rootElement;
		var that = this;
		
		var initialize = function() {
			this.system = system;
			this.destination = data.station;
			this.platform = data.platform;
			this.time = formatTime(data.time);
			this.delay = data.delay;
			this.timeWithDelay = formatTime(data.time + data.delay);
			if ( this.system == 'nmbs' ) {
				this.type = (data.vehicle.match(/\.([A-Z]+)\d+$/)||{1:''})[1];
			}
			if ( this.system == 'mivb' ) {
				this.line = (data.vehicle.match(/\d+$/)||{0:''})[0];
			}

			// TODO: find out how to actually get the cancelled status (not documented in api)
			// if( Math.floor(Math.random()*4)==0){
			// 	this.cancelled = true;
			// }
			
			initializeHtml();
			addBehaviours();			
		};


		var formatTime = function(timestamp) {
			var time = new Date(timestamp*1000),
			    hours = time.getHours(),
			    minutes=time.getMinutes();
			return (hours<10?'0':'')+hours + ':' + (minutes<10?'0':'')+minutes;
		};

		var initializeHtml = function() {
			rootElement = rootElement.templateReplace("LiveBoardRow", that);
		};

		var addBehaviours = function() {
		};

		var removeBehaviours = function() {
		};

		this.destroy = function(remove) {
			removeBehaviours();

			rootElement.empty();
			if (remove) {
				rootElement.remove();
				delete this;
			}
		}

		this.update = function() {
			that.destroy();
			initialize();
		}

		initialize.apply(this, arguments);
	};
	
})(jQuery);

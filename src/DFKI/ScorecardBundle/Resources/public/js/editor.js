
function time(){

    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    return h + ":" + m + ":" + s;

}
"use strict";
var sc = {

	api: "",
	projectId: -1,
        projectName: '',
	initSegment: -1,
	
	init: function(){
		this.selector.init();
		this.buttons.init();
		this.issueReports.init();
		this.sidebars.init();	
		this.goToSegment.init();
		this.highlight.init();
		this.scores.init();
		this.filter.init();
	},
        
        alert: {
            css: `
                display: block;
                position: absolute;
                width: 400px;
                min-height: 100px;
                padding: 10px;
                border: 2px solid red;
                background-color: #DCECF6;
                left: 40%;
                top: 40%;
                z-index: 1;
            `,
            onOk: function(){console.log("Ok")},
            onCancel: function(){console.log("Cancel")},
            show: function(message, onOk = sc.alert.onOk, onCancel = sc.alert.onCancel, ok = 'OK', cancel = 'Cancel'){
                sc.alert.onOk = onOk;
                sc.alert.onCancel = onCancel;
                $('#alertBox').attr('style', sc.alert.css);
                $('#alertBox').html($(
                        '<div style="width: 100%; min-height: 150px; text-align: center;">'+message+'</div>'+
                        '<div style="width: 100%; height: 100px;">'+
                            '<button onclick="javascript:sc.alert.onOk(); sc.alert.hide();" style="width: 160px; height: 40px; position: absolute; bottom: 30px; right: 30px; ">'+ok+'</button>'+
                            '<button onclick="javascript:sc.alert.onCancel(); sc.alert.hide();" style="width: 160px; height: 40px; position: absolute; bottom: 30px; left: 30px;">'+cancel+'</button>'+
                        '</div>'
                        ));
            },
            hide: function(){
                $('#alertBox').attr('style', '');
                $('#alertBox').html('');
            }
        },
        
        segmentIdNumLink: {
            key: function() {
                var key = {};
                
                $('#scorecard .segment-text').each(function(index, value) {
                        key[$(value).attr('segment-id')] = $(value).attr('segment-num');
                    }.bind(key));
                    
                return key;
            }
        },
        
        export: {
                getDataWithSegments: function(useRevised = false) {
                    var scorecardReport = sc.export.getData(useRevised);
                    scorecardReport['segments'] = sc.segments.get();
                    return scorecardReport;
                },
            
                getData: function(useRevised = false) {
                    var scorecardReport = {
                            'projectName': sc.projectName + "_" + sc.projectId,
                            'key': sc.segmentIdNumLink.key(),
                            'issues': sc.issueReports.reports,
                            'notes': sc.notes.get(),
                            'highlights': sc.highlight.getHighlights(),
                            'issueReport': sc.issueReports.getReport(),
                            'scores': sc.scores.getScores(),
                            'useRevised': useRevised
                        };
                        
                    return scorecardReport;
                },
            
                download: function(type, text) {
                        var filename = sc.projectName + "_" + sc.projectId + "_export_" + type + ".json";
                    
                        var element = document.createElement('a');
                        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                        element.setAttribute('download', filename);

                        element.style.display = 'none';
                        document.body.appendChild(element);

                        element.click();

                        document.body.removeChild(element);
                },

                all: function(withSegments = true, useRevised = false) {
                        var scorecardReport = (withSegments) ? sc.export.getDataWithSegments(useRevised) : sc.export.getData(useRevised);
                        sc.export.download('all', JSON.stringify(scorecardReport));
                },
                
                issues: function() {
                        sc.export.download("issues", JSON.stringify(sc.issueReports.reports));
                },
                
                notes: function() {
                        var notes = sc.notes.get();
                        sc.export.download("notes", JSON.stringify(notes));
                },
                
                highlights: function() {
                        var highlighted = sc.highlight.getHighlights();
                        sc.export.download("highlights", JSON.stringify(highlighted));
                }
                
        },

        segments: {
                get: function() {
                    var data = {
                        source: [],
                        target: []
                    }
                    
                    $('tr.segment-text .source').each(function(index, val){
                        data.source.push($(val).text());
                    }.bind(data));
                    
                    $('tr.segment-text .target').each(function(index, val){
                        data.target.push($(val).text());
                    }.bind(data));
                    
                    return data;
                }
        },
        
	issueReports:{
	
		reports: [],
		
		pendingCriticalIssue: null,

		addIssueReport: function(segment, target, name, severity, issueReportId, issueId){
			this.addIssueReportWithoutShow(segment, target, name, severity, issueReportId, issueId);
			this.show();
		},
		
		addIssueReportWithoutShow: function(segment, target, name, severity, issueReportId, issueId){
			this.reports.push({
				"segment": segment,
				"target": target,
				"name": name,
				"severity": severity,
				"issueReportId": issueReportId,
				"issueId" : issueId
			});
			
			if( typeof this.issuesPerSegment[segment] == "undefined"){
				this.issuesPerSegment[segment] = {};
			}
			this.issuesPerSegment[segment][issueId] = true;
		},
		
		issuesPerSegment: {},

                getAllIssues: function(){
                    var issues = [];
                    $('.metric .label').each(function(index, value){
                        issues.push($(value).text());
                    }.bind(issues))
                    
                    return issues;
                },
                
                getReport: function() {
                    return $('#report').prop('outerHTML');
                },
                
		remove: function(index){
			var issue = this.reports[index];
			delete this.issuesPerSegment[issue.segment][issue.issueId];
			this.reports.splice(index,1);
			this.show();
		},

		show: function(){
			$('#scorecard .issues td').each(function(){
				$(this).html('&nbsp;');
			});
			for( var i=0; i<this.reports.length; i++ ){
				var issueReport = this.reports[i];
				var html = '<div class="issue-report ' + issueReport.severity + '">' + issueReport.name;
				html += ' <a href="#" index="' + i + '" issue-report-id="' + issueReport.issueReportId + '" class="close">[x]</a></div>';
				$('tr[segment-id=' + issueReport.segment + '] td[class=issue-' + issueReport.target + ']').append(html);
			}
			$('table.segtable tr.issues div.issue-report a').click(function(){
				var index = $(this).attr("index");
				var reportid = $(this).attr("issue-report-id");
				sc.issueReports.remove(index);
				sc.issueReports.show();
				$.ajax({
				    url: sc.api + "editor/issues/" + reportid,
				    type: 'DELETE',
				    success: function(){
						sc.scores.updateScores();
				    },
				    data: {}
				  });
				return false;
			});
		},

		init: function(){
			var html = '<div id="add-critical-dialog" title="Add a critical issue?">Critical severity is reserved for errors that, by themselves, cause the project to fail to meet specifications. <strong>Adding a critical issue will cause this project to fail this quality check.</strong> Do you wish to add a critical issue? (You may also cancel or add it as a major issue.)</div>';
			$("body").append(html);
			$('#add-critical-dialog').dialog({
				width: 500,
				modal: true,
				autoOpen:false,
				buttons:{
					"Cancel": function(){
						$('#add-critical-dialog').dialog("close");
					},
					"Add critical issue": function(){
						var data = sc.issueReports.pendingCriticalIssue;
						sc.buttons.addIssue(data.issueid, data.issuename, data.target, "critical");
						$('#add-critical-dialog').dialog("close");
					},
					"Add as major": function(){
						var data = sc.issueReports.pendingCriticalIssue;
						sc.buttons.addIssue(data.issueid, data.issuename, data.target, "major");
						$('#add-critical-dialog').dialog("close");
					}
				}
			});
			
		}
	},
		
	selector: {

		selection: null,
		
		init: function(){
			var that = this;
			$('#scorecard .td-segtable tr').dblclick(function(){
				var selection = $(this).attr('segment-id');
				that.selectSegment(selection, true);
			});
		},
		selectSegment: function(segmentId, checkNotes = false){
                        if (checkNotes) {
                            if (sc.notes.checkChanged()) {
                                sc.alert.show(
                                        "You have unsaved notes for the current segment.  Would you like to save or discard them?",
                                        function(){
                                            sc.notes.save();
                                            sc.selector.selectSegment(segmentId);
                                        },
                                        function(){
                                            sc.selector.selectSegment(segmentId);
                                        },
                                        "Save",
                                        "Discard"
                                );
                        
                                return;
                            }
                        }
                    
			sc.selector.selection = segmentId;
			$('#scorecard tr').removeClass('selection-top');
			$('#scorecard tr').removeClass('selection-bottom');
			
			$('#scorecard tr[segment-id=' + segmentId + ']').first().addClass( 'selection-top' );
			$('#scorecard tr[segment-id=' + segmentId + ']').last().addClass( 'selection-bottom' );
			
			// show notes
			var notes = $('tr[segment-id=' + segmentId + ']').first().attr('notes');
			$('#segment-notes').val(notes);
			
			// scroll
			var top = ($('tr[segment-id=' + segmentId + ']').first().position().top - $('#scorecard').position().top)
				+ $('table.segtable').scrollTop() - 50;
			$('table.segtable').animate(
				{
					scrollTop: top,
				},
				200,
				'swing'
			);

			// set source / target headings
			var index = $('tr[segment-id=' + segmentId + '] td.yellow').html();
			$('.selected-segment').html(index);

			$.post( sc.api + "editor/segment/touch/" + segmentId );		
		}
	},

	buttons:{
		init: function(){
			$('#metrics div.metric').hover(
				function(){
					$("div.buttons-left", this).css("visibility", "visible");
					$("div.buttons-right", this).css("visibility", "visible");
				},
				function(){
					$("div.buttons-left", this).css("visibility", "hidden");
					$("div.buttons-right", this).css("visibility", "hidden");
				}
			);

			$('#metrics a').click(function(event){
				var severity = undefined;
				if( $(this).hasClass("minor"))
					severity = "minor";
				else if( $(this).hasClass("major"))
					severity = "major";
				else if( $(this).hasClass("critical"))
					severity = "critical";

				if( severity == undefined ){
					return false;
				}

				var target = $(this).attr("target");
				var issueid = $(this).attr("issue-id");
				var issuename = $(this).attr("issue-name");
				
				if( severity == "critical" ){
					sc.issueReports.pendingCriticalIssue = {
						"target": target,
						"issuename": issuename,
						"severity": severity,
						"issueid": issueid
					};
					$('#add-critical-dialog').dialog("open");
				} else{
					sc.buttons.addIssue(issueid, issuename, target, severity);
				}
				return false;
			});
			
			// disable accuracy - source and i18n - target
			$('tr[topmetric=accuracy] a[target=source]').addClass("dead").unbind("click").click(function(){ return false; });
			$('tr[topmetric=internationalization] a[target=target]').addClass("dead").unbind("click").click(function(){ return false; });
		},
		addIssue: function(issueid, issuename, side, severity){
			if( sc.selector.selection == null )
				return;

			var segment = sc.selector.selection;
			var tempReportId = "rand" + Math.random();
			sc.issueReports.addIssueReport(segment, side, issuename, severity, tempReportId, issueid);

			report.importIssues(sc.issueReports.reports);
			report.show();
						
			$.post(
				sc.api + "editor/issues",
				{
					"segmentid": segment,
					"issueid": issueid,
					"side": side,
					"severity" : severity,
					"tempReportId": tempReportId
				},
				function(response){
					// set report id
					for( var i=0; i<sc.issueReports.reports.length; i++ ){
						if( sc.issueReports.reports[i].issueReportId == response.tempReportId ){
							sc.issueReports.reports[i].issueReportId = response.id;
							sc.issueReports.show();
						}
					}
					
					sc.scores.updateScores();
				}
			);
		}
	},

	goToSegment: {
		init: function(){
			$('#go-to-seg').click(function(){
				var index = $('#go-to-seg-input').val()*2-1;
				var segmentid =  $($('#scorecard tr[segment-id]')[index]).attr("segment-id");
				sc.selector.selectSegment(segmentid, true);
			});
		}
	},
	
	// navigation and highlight buttons
	sidebars: {
		init: function(){
			$('table.nav a.nav_down').click(function(){
				var selection = sc.selector.selection;
				var ids = sc.sidebars.getIds();
				var pos = ids.indexOf(parseInt(selection));
				if( pos==-1){
					pos=0;
				}
				if( selection != null && pos>=0 && pos<ids.length-1){
					var nextid = ids[pos+1];
					sc.selector.selectSegment(nextid, true);
				}
				return false;
			});
			$('table.nav a.nav_up').click(function(){
				var selection = sc.selector.selection;
				var ids = sc.sidebars.getIds();
				var pos = ids.indexOf(parseInt(selection));
				if( pos==-1){
					pos=0;
				}
				if( selection != null && pos>=1){
					var nextid = ids[pos-1];
					sc.selector.selectSegment(nextid, true);
				}
				return false;
			});
		},
		// return array of all segment ids
		getIds: function(){
			var ids = Array();
			$('tr.segment-text:visible').each(function(){
				var id = parseInt($(this).attr('segment-id'));
				ids.push(id);
			});
			return ids;
		}
	},
	
	notes: {
                checkChanged: function(){
                    var segmentId = sc.selector.selection;
                    var old_notes = $('tr[segment-id=' + segmentId + ']').first().attr('notes');
                    var current_notes = $('#segment-notes').val();
                    
                    return (old_notes !== current_notes) ? true: false;
                },
		save: function(){
			var segment = sc.selector.selection;
			var notes = $('#segment-notes').val();
			
			$('tr[segment-id=' + segment + ']').first().attr('notes', notes.replace('"', '&quot;'));
			$.post(
				sc.api + "editor/notes",
				{
					"segment": segment,
					"notes": notes,
				}
			);
		},
                get: function(segmentId = null) {
                        if (segmentId) {
                            return $('#scorecard .segment-text[segment-id="'+ segmentId +'"]').attr('notes');
                        }

                        let notes = []

                        $('#scorecard .segment-text').each(function(index, value) {
                            let segmentNotes = $(value).attr('notes');

                            if (segmentNotes.length > 0) {
                                notes.push({'segment-id': $(value).attr('segment-id'), notes: segmentNotes});
                            }
                        }.bind(notes));
                        
                        return notes;
                }
	},
	
	highlight: {
		
		highlighters: [],
		active: false,
		
		init: function(){
			var that = this;
			
			$('tr.segment-text td').each(function(){
				var segmentid = $(this).parent().attr('segment-id');
				var side = $(this).attr("class");
				var hltr = new TextHighlighter(this, {
					onBeforeHighlight: function(){
						if( segmentid != sc.selector.selection ){
							return false;
						} else if( !sc.highlight.active ){
							return false;
						} else{
							return true;
						}
					},
					onAfterHighlight: function(range, hlts ){
						sc.highlight.saveHighlights(segmentid, side);
					}
				});
				hltr.setColor("#FFFF7B");
				that.highlighters[segmentid + "-" + side] = hltr;
			});	

			$('a.set-highlight-off').click(function(){
				sc.highlight.active = !sc.highlight.active;
				if( sc.highlight.active ){
					$('a.set-highlight-off').attr("class", "set-highlight-on");
				} else{
					$('a.set-highlight-on').attr("class", "set-highlight-off");
				}
				return false;
			});
			
			$('a.clear-highlight-source').click(function(){
				sc.highlight.highlighters[sc.selector.selection + "-source" ].removeHighlights();
				sc.highlight.saveHighlights(sc.selector.selection, "source");
				return false;
			});
			
			$('a.clear-highlight-target').click(function(){
				sc.highlight.highlighters[sc.selector.selection + "-target" ].removeHighlights();
				sc.highlight.saveHighlights(sc.selector.selection, "target");
				return false;
			});
		},
		
		saveHighlights: function(segmentid, side){
			// save highlights
			var str = sc.highlight.highlighters[segmentid+"-"+side].serializeHighlights();
			$.post(
				sc.api + "editor/segments/" + segmentid,
				{
					highlights: str,
					"side": side
				}
			);
		},
		
                getHighlights: function(segmentId = null) {
                    if (segmentId) {
                        let highlighted = {
                            source: [],
                            target: []
                        }
                        
                        $('#scorecard .segment-text[segment-id="' + segmentId + '"] .source .highlighted').each(function(index, value){
                            highlighted.source.push($(value).text());
                        }.bind(highlighted));
                        
                        $('#scorecard .segment-text[segment-id="' + segmentId + '"] .target .highlighted').each(function(index, value){
                            highlighted.target.push($(value).text());
                        }.bind(highlighted));
                        
                        return highlighted;
                    }
                    
                    let highlighted = [];
                    
                    $('#scorecard .segment-text').each(function(index, value) {
                        var highlightedChildren = $(value).find('.highlighted');
                        
                        if (highlightedChildren.length < 1) return;
                        
                        var segmentId = $(value).attr('segment-id');

                        var highlightGroup = {
                            'segment-id': segmentId,
                            'highlights': sc.highlight.getHighlights(segmentId)
                        }

                        highlighted.push(highlightGroup);                        
                    }.bind(highlighted));
                    
                    return highlighted;
                },
                
		setHighlights: function(segmentid,str, side){
			this.highlighters[segmentid + "-" + side].deserializeHighlights(str);
		}
	},
	
	scores: {
		init: function(){
			this.updateScores();
		},
		
		formatScore: function(score){
			return Math.round(score*100)/100 + "&#037;";
		},
		
		updateScores: function(){
			$.get(
				sc.api + "editor/projectscore/" + sc.projectId,
				function(response){
					$('#sourceScore').html(sc.scores.formatScore(response.sourceScore));
					$('#targetScore').html(sc.scores.formatScore(response.targetScore));
					$('#compositeScore').html(sc.scores.formatScore(response.compositeScore));
				});
		},
                
                getScores: function() {
                    return {
                        'sourceScore': $('#sourceScore').html(),
                        'targetScore': $('#targetScore').html(),
                        'compositeScore': $('#compositeScore').html()
                    }
                }
	},
	

	filter: {
		
		init: function(){
						
			$('#filter_text').val("");
			$('#filter_text').keyup(function(){
				$('#advanced_filter_input').val($('#filter_text').val());
				sc.filter.applyFilter();
			});
			$('#clearFilter').click(function(){sc.filter.clearFilter(); return false;});
			
			$('#advanced_filter_dialog').dialog({
				width:500,
				autoOpen: false,
				buttons: {
					"Clear Filter": function(){
						sc.filter.clearFilter();
					},
					"Close": function(){
						$('#advanced_filter_dialog').dialog("close");
					}
				}
			});
			
			$('#advanced_filter_input').keyup(function(){
				$('#filter_text').val($('#advanced_filter_input').val());
				sc.filter.applyFilter();
			});
			
			$('#advanced_filter_dialog input[type=checkbox]').click(function(){
				sc.filter.applyFilter();
			});
			
			$('#advanced_filter').click(function(){
				$('#advanced_filter_dialog').dialog('open');
			});
		},
		
		applyFilter: function(){
		
			var filterText = $('#filter_text').val().toLowerCase();
			var filterIssues = [];
			var filterForIssues = false;
			var filterForText = filterText.trim().length > 0;
			
			$('#advanced_filter_dialog input:checked').each(function(){
				var issueId = $(this).attr("issue");
				filterIssues.push(issueId);
				filterForIssues = true;
			});

			var countTotal=0;
			var countVisible=0;
			
			$('#scorecard tr.hide').removeClass("hide");
			
			if( filterText.trim().length == 0 && !filterForIssues ){
				$('#filterApplied').hide();
			} else{
				$('#scorecard tr.segment-text').each(function(){
					countTotal++;
					var tr = $(this);
					var segmentId = tr.attr("segment-id");
	
					var textMatch = true;
					if( filterForText ){
						var sourceText = tr.children("td.source").children("div").html().toLowerCase();
						textMatch = sourceText.indexOf(filterText) >= 0;
						if( !textMatch ){
							var targetText = tr.children("td.target").children("div").html().toLowerCase();
							textMatch = targetText.indexOf(filterText) >= 0;
						}
					}
					
					var issueMatch = true;
					if( filterForIssues){
						issueMatch = false;
						if( typeof sc.issueReports.issuesPerSegment[segmentId] != "undefined" ){
							
							var foundAll = true;
							for( index in filterIssues ){
								var issue = filterIssues[index];
								if( typeof sc.issueReports.issuesPerSegment[segmentId][issue] == "undefined" ){
									foundAll = false;
									break;
								}
							}
							
							if( foundAll ){
								issueMatch = true;
							}
						}
					}
					
					if( textMatch && issueMatch ){
						countVisible++;
					} else{
						$("tr[segment-id=" + segmentId + "]").addClass("hide");
					}
				});
				
				if( countVisible == countTotal ){
					$('#filterApplied').hide();
				} else{
					$('#filterApplied').show();
				}
				$('#filterAppliedCount').html(countVisible);
			}
		},
		
		clearFilter:function(){
			$('#filter_text').val("");
			$('#advanced_filter_input').val("");
			$('#advanced_filter_dialog input:checked').attr("checked", false);
			sc.filter.applyFilter();
		}
	}
};
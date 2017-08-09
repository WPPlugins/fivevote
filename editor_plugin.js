(function() {
        // Load plugin specific language pack
        //tinymce.PluginManager.requireLangPack('fivevote');
        tinymce.create('tinymce.plugins.fiveVotePlugin', {
                /**
                 * Initializes the plugin, this will be executed after the plugin has been created.
                 * This call is done before the editor instance has finished it's initialization so use the onInit event
                 * of the editor instance to intercept that event.
                 *
                 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
                 * @param {string} url Absolute URL to where the plugin is located.
                 */
                init : function(ed, url) {
                	//debugger;
                	//console.log("URL: "+url);
                	//console.dir(ed);
                    // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mcefivevote');
                   
                   
                    ed.addCommand('mcefivevote', function() {
                            ed.windowManager.open({
                            		file : url + '/editor_dialog.htm',	//My form in essence goes here
                                    //width : 320,
                                    //height: 120,
                                    width : 550 + ed.getLang('fivevote.delta_width', 0),
                                    height : 500 + ed.getLang('fivevote.delta_height', 0),
                                    inline : 1
                            }, {
                                    plugin_url : url // Plugin absolute URL
                            });
                    });
                    
                    
                    
                
                    // Register fivevote button
                    ed.addButton('fivevote', {
                            //title : 'fivevote.title',
                            cmd : 'mcefivevote',
                            image : url + '/image.png',
                            //onclick: function(){
	                        //    console.log("Button has been clicked");
                            //}
                    });
                    
                    //debugger;

                    // Add a node change handler, selects the button in the UI when a image is selected
                    ed.onNodeChange.add(function(ed, cm, n) {
                            cm.setActive('fivevote', n.nodeName == 'IMG');
                    });
                },

                /**
                 * Returns information about the plugin as a name/value array.
                 * The current keys are longname, author, authorurl, infourl and version.
                 *
                 * @return {Object} Name/value array containing information about the plugin.
                 */
                
                getInfo : function() {
                        return {
                                longname : 'The 5 Vote Plugin',
                                author : 'Ben Hall',
                                authorurl : 'http://www.benhallbenhall.com',
                                infourl : 'http://www.benhallbenhall.com',
                                version : "1.0"
                        };
                }
             
        });

        // Register plugin
        tinymce.PluginManager.add('fivevote', tinymce.plugins.fiveVotePlugin);
})();
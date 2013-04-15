$(document).ready(function(){

    $("#browser").treeview();
    
    $("#myDiv").contextMenu({
        menu: 'myMenu'
    },
    function(action, el, pos) {
        alert(
            'Action: ' + action + '\n\n' +
            'Element ID: ' + $(el).attr('id') + '\n\n' +
            'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
            'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
            );
    });

    $("#myList UL LI").contextMenu({
        menu: 'myMenu'
    }, function(action, el, pos) {
        var id = $(el).text();

        if(action == "edit") {

            if(id == 'index') {
                id = '';
            }

            $.ajax({
                type: 'GET',
                url: 'js/Ajax-PHP/Admin/Gestion-pages/affichage.php',
                data: {
                    identif : id
                },

                success:
                function(result) {
                    var obj = jQuery.parseJSON(result);
                    var page = obj.page;
                    var id = obj.idurl;
                    var auto = obj.authorized;

                    var method = obj.method;
                    var view = obj.view;
                    var type = obj.type;
                    var level = obj.level;
                    var title = obj.title;
                    var design = obj.design;
                    var description = obj.description;

                    $("#page2").attr('value', page);
                    $("#id2").attr('value', id);
                    $("#authorized2").attr('value', auto);
                    $("#method2").attr('value', method);
                    $("#view2").attr('value', view);
                    $("#type2").attr('value', type);
                    $("#level2").attr('value', level);
                    $("#title2").attr('value', title);
                    $("#design2").attr('value', design);
                    $("#description2").attr('value', description);
                    $('#edit').dialog('open');
                }
            });
            $.ajax({
                type: 'GET',
                url: 'js/Ajax-PHP/Admin/Gestion-pages/affichage-class.php',
                data: {
                    identif : id
                },

                success:
                function(result) {
                    $("#class2").attr('value', result);
                }
            });
        }

        if(action == "show") {

            if(id == 'index') {
                id = '';
            }

            $.ajax({
                type: 'GET',
                url: 'js/Ajax-PHP/Admin/Gestion-pages/affichage.php',
                data: {
                    identif : id
                },

                success:
                function(result) {
                    var obj = jQuery.parseJSON(result);
                    var page = obj.page;
                    var id = obj.idurl;
                    var auto = obj.authorized;

                    if (auto == 0) {
                        auto = 'Oui';
                    }
                    
                    if (auto == 1) {
                        auto = 'Non';
                    }

                    var method = obj.method;
                    var view = obj.view;
                    var type = obj.type;
                    var level = obj.level;
                    var title = obj.title;
                    var design = obj.design;
                    var description = obj.description;
                    
                    $("#page").attr('value', page);
                    $("#id").attr('value', id);
                    $("#authorized").attr('value', auto);
                    $("#method").attr('value', method);
                    $("#view").attr('value', view);
                    $("#type").attr('value', type);
                    $("#level").attr('value', level);
                    $("#title").attr('value', title);
                    $("#design").attr('value', design);
                    $("#description").attr('value', description);
                    $('#show').dialog('open');
                }
            });
            $.ajax({
                type: 'GET',
                url: 'js/Ajax-PHP/Admin/Gestion-pages/affichage-class.php',
                data: {
                    identif : id
                },

                success:
                function(result) {            
                    $("#class").attr('value', result);
                }
            });
        }

        if(action == "delete") {
            if(id == 'index') {
                id = '';
            }
            $("#page3").attr('value', id);
            $('#delete').dialog('open');
        }

        if(action == "add") {
            $('#add').dialog('open');
        }
    });

    $("#disableMenus").click( function() {
        $('#myDiv, #myList UL LI').disableContextMenu();
        $(this).attr('disabled', true);
        $("#enableMenus").attr('disabled', false);
    });

    $("#enableMenus").click( function() {
        $('#myDiv, #myList UL LI').enableContextMenu();
        $(this).attr('disabled', true);
        $("#disableMenus").attr('disabled', false);
    });

    $("#disableItems").click( function() {
        $('#myMenu').disableContextMenuItems('#cut,#copy');
        $(this).attr('disabled', true);
        $("#enableItems").attr('disabled', false);
    });

    $("#enableItems").click( function() {
        $('#myMenu').enableContextMenuItems('#cut,#copy');
        $(this).attr('disabled', true);
        $("#disableItems").attr('disabled', false);
    });

    $('#show').dialog({
        autoOpen: false,
        width: 700,
        buttons: {
            "Fermer": function() {
                $(this).dialog("close");
            }
        }
    });

    $('#edit').dialog({
        autoOpen: false,
        width: 700,
        buttons: {
            "Modifier": function() {
                $.ajax({
                    type: 'GET',
                    url: 'js/Ajax-PHP/Admin/Gestion-pages/modifier.php',
                    data: {
                        page : $("#page2").val(),
                        id : $("#id2").val(),
                        auto : $("#auto2").val(),
                        method : $("#method2").val(),
                        view : $("#view2").val(),
                        classe : $("#class2").val(),
                        level : $("#level2").val(),
                        type : $("#type2").val(),
                        title : $("#title2").val(),
                        design : $("#design2").val(),
                        description : $("#description2").val()
                    },

                    success:
                    function() {
                        location.reload();
                    }
                });
            },
            "Fermer": function() {
                $(this).dialog("close");
            }
        }
    });


    $('#delete').dialog({
        autoOpen: false,
        width: 700,
        buttons: {
            "Accepter": function() {
                $.ajax({
                    type: 'GET',
                    url: 'js/Ajax-PHP/Admin/Gestion-pages/supprimer.php',
                    data: {
                        identif : $("#page3").val()
                    },

                    success:
                    function() {
                        location.reload();
                    }
                });
            },
            "Refuser": function() {
                $(this).dialog("close");
            }
        }
    });

    $('#add').dialog({
        autoOpen: false,
        width: 700,
        buttons: {
            "Ajouter": function() {
                $.ajax({
                    type: 'GET',
                    url: 'js/Ajax-PHP/Admin/Gestion-pages/ajouter.php',
                    data: {
                        page : $("#page4").val(),
                        auto : $("#authorized4").val(),
                        classe : $("#class4").val(),
                        method : $("#method4").val(),
                        level : $("#level4").val(),
                        type : $("#type4").val(),
                        view : $("#view4").val(),
                        design : $("#design4").val(),
                        title : $("#title4").val(),
                        description : $("#description4").val()
                    },

                    success:
                    function() {
                        location.reload();
                    }
                });
            },
            "Fermer": function() {
                $(this).dialog("close");
            }
        }
    });
});
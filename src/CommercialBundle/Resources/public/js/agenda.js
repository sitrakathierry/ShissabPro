$(document).ready(function() {

        var events = [];

        function modal_evenement(id) {
            id = typeof id !== 'undefined' ? id : 0;

            $.ajax({
                data: {
                    id: id
                },
                type: 'POST',
                url: Routing.generate('commercial_evenement_editor'),
                dataType: 'html',
                success: function(data) {
                    show_modal(data,'Création');
                    $('.clockpicker').clockpicker();
                    $('.input-datepicker').datepicker({
                        todayBtn: "linked",
                        keyboardNavigation: false,
                        forceParse: false,
                        calendarWeeks: true,
                        autoclose: true,
                        format: 'dd/mm/yyyy',
                        language: 'fr',
                    });
                    $('.summernote').summernote();
                }
            });
        }

        function save_evenement(){
            var id = $(this).closest('div.form-horizontal').find('#evenement_id').val();
            var titre = $(this).closest('div.form-horizontal').find('#evenement_titre').val();
            var description = $(this).closest('div.form-horizontal').find('#evenement_description').code();
            var type = $(this).closest('div.form-horizontal').find('#evenement_type').val();
            var heure = $(this).closest('div.form-horizontal').find('#evenement_heure').val();
            var date = $(this).closest('div.form-horizontal').find('#evenement_date').datepicker("getDate");
            var datestr = $(this).closest('div.form-horizontal').find('#evenement_date').val() + ' ' + heure;
            var couleur = '#fd9597';
            
            heure = heure.split(':')
            date.setHours(heure[0])
            date.setMinutes(heure[1])

            var evenement  = {
                id : typeof id !== 'undefined' ? id : uniqid('sh_'),
                title: titre,
                start: date,
                description : description,
                backgroundColor : couleur,
                type : type,
                datestr : datestr
            };

            var url = Routing.generate('commercial_evenement_save');

            $.ajax({
                url : url,
                type : 'POST',
                data : evenement,
                success: function(res) {
                    show_info('Succès','Création éffectué');
                    $('#calendar').fullCalendar('renderEvent', evenement);
                }
            })


        }

        $(document).on('click', '.btn-show-modal-evenement', function(event) {
            event.preventDefault();
            modal_evenement();      
        });

        
        $(document).on('click', '#btn-add-event', function(event) {
            event.preventDefault();
            save_evenement.call(this);
        });

        init_calendar();

        function init_calendar() {
            
            $('#calendar').fullCalendar({
                lang: 'fr',
                theme: false,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText : {
                    today:    "Aujourd'hui",
                    month:    'Mois',
                    week:     'Semaine',
                    day:      'Jour',
                },
                height : "auto",
                editable: true,
                droppable: true,
                weekNumbers: true,
                selectable: true,
                drop: function() {
                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                events: events,
                eventMouseover: function (event, jsEvent) {
                    var tooltip = '<div class="tooltipevent" style="padding: 10px; padding-top: 15px; padding-bottom: 15px; background-color: rgba(255, 255, 255, 0.8); border: 1px solid gray; border-radius: 2px; position:absolute;z-index:10001;">' + event.description + '</div>';
                    var $tooltip = $(tooltip).appendTo('body');

                    $(this).mouseover(function (e) {
                        $(this).css('z-index', 10000);
                        $tooltip.fadeIn('500');
                        $tooltip.fadeTo('10', 1.9);
                    }).mousemove(function (e) {
                        $tooltip.css('top', e.pageY + 10);
                        $tooltip.css('left', e.pageX + 20);
                    });
                },
                eventMouseout: function (event, jsEvent) {
                    $(this).css('z-index', 8);
                    $('.tooltipevent').remove();
                },
                select: function (start, end) {
                    console.log('okok')
                },
                eventClick: function (event, element) {
                    idEvent = event.id;
                    if (idEvent < 0) {
                        idEvent = 0;
                    }

                    console.log(event)
                    // openModalShowEvent(idEvent, '', '', 1);
                },
                eventRender: function (event, element) {
                    console.log(event.id)

                    if (event.isDone) {
                        element.html("<span class='fa fa-check' ></span>" + element.html());
                    }
                    else if (event.backgroundColor == '#CF000F') {
                        element.html("<span class='fa fa-clock' ></span>" + element.html());
                    }
                    if (event.type == 1) {
                        element.html("<span class='fa fa-user' ></span>" + element.html());
                    }
                    else if (event.type == 2) {
                        element.html("<span class='fa fa-phone' ></span>" + element.html());
                    }
                },
            });
        }




    });
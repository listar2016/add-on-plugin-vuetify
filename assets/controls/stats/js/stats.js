jQuery( window ).on( 'elementor:init', function() {
	var ControlBaseMultipleItemView = elementor.modules.controls.BaseMultiple;
    var ControlItemView = ControlBaseMultipleItemView.extend( {
        ui: function() {
            var ui = ControlBaseMultipleItemView.prototype.ui.apply( this, arguments );
            ui.stats = '.elementor-control-stats';
            ui.sortableContainer = '.js-sortable';
            ui.sortableEl = '.js-sortable .sortable-li';
            return ui;
        },    
        onReady: function() {
            var self = this,
                currentValue = self.getControlValue();
                sortable(this.ui.sortableContainer);
        },
        events: function() {
            return _.extend( ControlBaseMultipleItemView.prototype.events.apply( this, arguments ), {
                'dragenter @ui.sortableEl': function(e) {
                    e.preventDefault();
                },
                'dragover @ui.sortableEl': function(e) {
                    e.preventDefault();
                },
                'dragend @ui.sortableEl': 'onDragend'
            } );
        },
        onDragend: function(e){
            var orderingData = {};
            jQuery('.js-sortable .sortable-li').each( function( index, element ){
                var elKey = jQuery(element).data('key');
                orderingData[elKey] = index + 1;
            });
            this.setValue(orderingData);
            this.applySavedValue();
           
        }
    });
 elementor.addControlView( 'statistics-ordering', ControlItemView );

});

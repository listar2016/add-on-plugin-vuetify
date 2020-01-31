<?php
namespace ElementorExpressAddOns\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class Control_Statistics_Ordering extends \Elementor\Control_Base_Multiple {

	static $TYPE = 'statistics-ordering';
    
    public function get_type() {
		return self::$TYPE;
	}

	protected function get_default_settings() {
		return [
			'label_block' => true
		];
	}

	public function enqueue(){
		wp_register_style('stats-style', plugins_url('/assets/controls/stats/css/stats.css', dirname(dirname(__FILE__)) ), [], '1.0.0' );
		wp_register_script( 'html5sortable', plugins_url('/assets/libs/html5sortable.min.js', dirname(dirname(__FILE__)) ), [], '1.0.0' );
		wp_register_script( 'statistics-ordering-control', plugins_url('/assets/controls/stats/js/stats.js', dirname(dirname(__FILE__)) ), ['html5sortable'], '1.0.0' );
		wp_enqueue_script( 'statistics-ordering-control' );
		wp_enqueue_style( 'stats-style' );
	}
	
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<#
            var sort = function( value ){
                var sortable = [];
                for (var v in value) {
                    sortable.push([v, value[v]]);
                }

                sortable.sort(function(a, b) {
                    return a[1] - b[1];
                });
                var objSorted = {}
                sortable.forEach(function(item){
                    objSorted[item[0]]=item[1]
                })
                return objSorted;
            }
			var printOptions = function( controlValue, options ) {
				_.each( controlValue, function( value, key) { #>
						<li class="sortable-li" data-key="{{{ key }}}">{{{ options[key] }}}</li>
				<# } );
			};
			var controlValue = sort(data.controlValue);
		#>	
		<div class="elementor-control-field elementor-control-stats">
			<# if ( data.label ) {#>
				<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper">
				<ul class="js-sortable sortable">
					<#
						printOptions(controlValue, data.options);
					#>
				</ul>
			</div>	
			<# if ( data.description ) { #>
				<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<input type="hidden" id="<?php echo $control_uid; ?>" class="elementor-control-stats-ordering-column" data-setting="{{ data.name }}"/>
		</div>
		
		
		<?php
	}
}

/**
 * Test Module Admin Script
 *
 * @since	1.0.0
 * @package obfx_modules/test/js
 *
 * @author	ThemeIsle
 */

/* jshint unused:vars */
/* global console, Chart, jQuery */
var stats_module = function( $ ) {
	'use strict';
	$( function() {
		if ( $( '#obfxChart' ).length ) {
			var myChart;
			var ctx = document.getElementById( 'obfxChart' ).getContext( '2d' );
			var posts_data = $( '#obfxChart' ).data( 'posts' );
			var comments_data = $( '#obfxChart' ).data( 'comments' );

			var dataset = [];
			if ( typeof posts_data !== 'undefined' ) {
				dataset.push( {
					label: '# of Posts',
					data: posts_data,
					backgroundColor: 'rgba( 3, 169, 244, 0.5 )',
					borderColor: 'rgba( 3, 169, 244, 1 )',
					borderWidth: 1
				} );
			}

			if ( typeof comments_data !== 'undefined' ) {
				dataset.push( {
					label: '# of Comments',
					data: comments_data,
					backgroundColor: 'rgba( 255, 152, 0, 0.5 )',
					borderColor: 'rgba( 255, 152, 0, 1 )',
					borderWidth: 1
				} );
			}

			console.log( ctx );
			myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: dataset
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
		}// End if().
	} );

};
stats_module( jQuery );

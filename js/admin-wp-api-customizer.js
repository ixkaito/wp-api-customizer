/*
Plugin Name: WP API Customizer
Plugin URI:  https://github.com/ixkaito/wp-api-customizer
Author:      KITE
Author URI:  http://kiteretz.com
Version:     0.0.2
Description:
Text Domain: wp-api-customizer
Domain Path: /languages
License:     GPLv2
*/
(function($){

  var $options = $('#wp-api-customizer-options');
  var $tbody   = $options.find('#the-list');
  var $input   = $tbody.find('input');

  function init(){
    $input.each(function(){
      var key  = $(this).closest('tr').index();
      var id   = $(this).attr('id');
      var name = $(this).attr('name');

      id   = id.replace(/(wp-api-customizer-options_)\d+(_.*)$/g, '$1' + key + '$2');
      name = name.replace(/(wp-api-customizer-options\[)\d+(\].*)$/g, '$1' + key + '$2');

      $(this).attr('id', id);
      $(this).attr('name', name);
    });
  }

  function add(){
    var key = $tbody.children().length;
    var tr  = '<tr>' +
      '<th scope="row" class="column-remove">' +
        '<a class="dashicons-before dashicons-minus remove-option" href="#"></a>' +
      '</th>' +
      '<td>' +
        '<input type="text" id="wp-api-customizer-options_' + key + '_json-attribute" class="text" name="wp-api-customizer-options[' + key + '][json-attribute]" value="" placeholder="">' +
      '</td>' +
      '<td>' +
        '<input type="text" id="wp-api-customizer-options_' + key + '_custom-field-name" class="text" name="wp-api-customizer-options[' + key + '][custom-field-name]" value="" placeholder="">' +
      '</td>' +
    '</tr>';
    $tbody.append(tr);
    init();
  }

  function remove(){
    $(this).closest('tr').remove();
    init();
  }

  init();
  $options.on('click', '.add-option', add);
  $options.on('click', '.remove-option', remove);

})(jQuery);
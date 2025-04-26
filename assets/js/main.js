var $ = window.jQuery;


jQuery( document ).ready(function() {
	
	//change title of yarpp
	jQuery('html[lang=en-GB] body.archive .yarpp-related h3').html('RECENT ARTICLES');
	jQuery('html[lang=zh-HK] body.archive .yarpp-related h3').html('最近新聞');
	jQuery('html[lang=zh-CN] body.archive .yarpp-related h3').html('最近新闻');	
	//end
  if(!jQuery('body').hasClass("elementor-editor-active")) {
    // Custom Accordion Element
  	var accordionActive = false;
  	jQuery('.custom-accordion-heading').on('click', function() {
  		if (jQuery(this).hasClass('active')) {
  			jQuery('.custom-accordion-content').hide();
  			jQuery('.custom-accordion-heading').removeClass('active');
  		}else {
  			jQuery('.custom-accordion-content').hide();
  			jQuery('.custom-accordion-heading').removeClass('active');

  			jQuery(this).addClass('active');
  			jQuery(this).next().show();
  		}
  	});
  };


  /**
   * ============================
   * Navigation
   * ============================
   */
  // Start Mobile Navigation
  jQuery('.site-navigation-dropdown ul.menu li.menu-item-has-children').append('<span class="sf-sub-indicator nav-sub-closed"><svg width="8" height="6" viewBox="0 0 8 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.28554 5.08551L7.8819 1.48795C8.03937 1.33009 8.03937 1.07434 7.8819 0.916078C7.72444 0.758219 7.46869 0.758219 7.31123 0.916078L4.00022 4.22829L0.689203 0.916476C0.531743 0.758618 0.275987 0.758618 0.118128 0.916476C-0.0393324 1.07434 -0.0393324 1.33049 0.118128 1.48835L3.7145 5.08591C3.87033 5.24138 4.1301 5.24138 4.28554 5.08551Z" fill="#302A2A"/></svg></span>')
  jQuery('.site-navigation-dropdown ul.menu li.menu-item-has-children > .sf-sub-indicator').on('click', function(e) {
    e.preventDefault();
    if (jQuery(this).parent().hasClass('elementor-active')) {
      jQuery(this).parent().removeClass('elementor-active');
    }else {
      jQuery(this).parent().addClass('elementor-active');
    }
  });
  // End Mobile Navigation

    

  /*Search Button*/
  jQuery('#site-search').on('click', function() {
    if (jQuery(this).hasClass('active')) {
      jQuery('#search-form').hide();
      jQuery(this).removeClass('active');
    }else {
      jQuery('#search-form').hide();
      jQuery(this).removeClass('active');

      jQuery(this).addClass('active');
      jQuery('#search-form').show();
    }
  });


  /*Close everything*/
  jQuery(document).on('click', function(event) {
    if(!jQuery(event.target).closest('#search-form, #site-search').length) {
      jQuery('#search-form').hide();
      jQuery('#site-search').removeClass('active');
    } 
  });
 jQuery('.um-account-tab-notifications .um-account-heading').html('<i class="um-faicon-envelope"></i>Newsletters');
jQuery('#um_account_submit_notifications').val('Update Newsletters');
jQuery('a[data-tab="notifications"] .um-account-title').html('Newsletters');

});
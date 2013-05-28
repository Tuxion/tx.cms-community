includeTranslations('community');

jQuery(function($){
  
  var prependError = function(xhr, state, message){
    //Add the generic error message.
    $wrapper.find('.restform-error-message').remove();
    var $error = $('<div>', {
      'class': 'restform-error-message',
      'text': message
    });
    $wrapper.prepend($error);
  };
  
  //Joining groups
  $('.community .join-user-group-profile').on('click', function(e){
    
    e.preventDefault();
    
    $wrapper = $(this).closest('div');
    
    $.rest('POST', '?rest=community/user_group_profile_application/'+$(this).attr('data-id'))
      .done(function(result){
        window.location = window.location;
      })
      .error(prependError);
    
  });
  
  //Leaving / withdrawing applications for groups.
  $('.community .leave-user-group-profile').on('click', function(e){
    
    e.preventDefault();
    
    $wrapper = $(this).closest('div');
    
    $.rest('DELETE', '?rest=community/user_group_profile_application/'+$(this).attr('data-id'))
      .done(function(result){
        window.location = window.location;
      })
      .error(prependError);
    
  });
  
  //Respond to group applications.
  $('.community .user-group-application-button').on('click', function(e){
    
    e.preventDefault();
    
    $wrapper = $(this).closest('li');
    
    $.rest('PUT', '?rest=community/user_group_profile_application_response', {
      user_id: $(this).attr('data-uid'),
      user_group_id: $(this).attr('data-gid'),
      response: $(this).attr('data-response')
    }).done(function(result){
      window.location = window.location;
    }).error(prependError);
    
  });
  
  //Deleting groups.
  $('.community .delete-user-group-profile').on('click', function(e){
    
    e.preventDefault();
    
    if(confirm(transf('community', 'This will PERMANENTLY delete the profile "{0}", are you sure?', $(this).attr('data-title')))){
      
      $.rest('DELETE', '?rest=community/user_group_profile/'+$(this).attr('data-id'))
        .done(function(result){
          window.location = window.location;
        })
        .error(prependError);
      
    }
    
  });
  
});
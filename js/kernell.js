jQuery(document).ready(function($){
    
    $("myTag").hover(function(){        
        $(this).css("color", "#17ba6f");
    }, function(){
        $(this).css("color", "white");
    });
    
    $("figure").hover(function(){
        var id=$(this).attr("attr-id");       
        $( "myTag[att-id*='x-"+id+"']" ).attr( "style","color:#17ba6f;" );
    }, function(){
        var id=$(this).attr("attr-id");
        $( "myTag[att-id*='x-"+id+"']" ).attr( "style","color:white" );   
    });
  
      onScroll = function (e){
   var maxScroll=1200
   if(e.target.scrollLeft>maxScrollLeft){
     e.target.scrollLeft=maxScrollLeft 
   }
}

});
    

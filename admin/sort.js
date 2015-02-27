$("document").ready(function(){
	$(".container .item").hover(function(){
		var item = $(this).parent().find(".item").attr("id");
		$(this).parent().find(".item span").hide();
		$(this).parent().find(".item input").fadeIn(270);
	}, function(){
			if($(".item input").css("border")=="solid 2px #b2f47d"){}else{
				$(this).parent().find(".item input").hide();
				$(this).parent().find(".item span").fadeIn(200);
		}
	}
	);
	$(".item input").click(function(){
		$(this).css("border", "solid 2px #b2f47d");
		$(this).val("");
		return false;
	});
	$(".item input").keypress(function(e){
            if(e.which == 13)
            {
                newpos(this,0);
            }
        });
	
});
function newpos(obj,act){
                if(act == 0){
                    var new_pos = parseInt($(obj).val());
                }     
                if(act == 1 || act == 2)
                {
                    if(act == 1)
                        var pred = parseInt($(obj).parent().parent().nextAll( '.container:first' ).children('.item').children('.inp').val());
                    else
                        var pred = parseInt($(obj).parent().parent().prevAll( '.container:first' ).children('.item').children('.inp').val());
                    
                    if(pred || pred == 0){
                        new_pos = pred;
                    }
                    else
                    {
                        new_pos = 0;
                    }
                    
                }
                var id = $(obj).attr("id"),
                table = $(obj).attr("rel"),
                cat = $(obj).attr("cat"),
                cat_val = $(obj).attr("cat_val"),
                old = $(obj).parent().attr('id');
			$.ajax({
				   type: "POST",
				   url: "/ajax/sort.php",
				   data: "id="+id+"&&"+"new="+new_pos+"&&old="+old+"&&table="+table+"&cat="+cat+'&cat_val='+cat_val,
				   success: function(data){
                                      {
                                        location.reload();
                                       }
			}
			});
	return false;	
}

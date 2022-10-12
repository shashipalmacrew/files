<!-- Pop up with html and css -->
<div id="free-trial-pop" class="primary-sidebar widget-area" role="complementary"><div class="close-wrap"><img src="/wp-content/uploads/2022/09/close.png" class="close-free-trial"></div><iframe src="https://beacon.trekmedics.org/login" title="trek Medics Login">
</iframe><!------Pop up body content------>
</div>

<script>
	//pop up
  	$('.free-trial-btn').click(function(){
  		$('#free-trial-pop').fadeToggle("slow");
  	});

 	 $('.close-free-trial').click(function(){
  		$('#free-trial-pop').fadeOut("slow");
  	});

  	$("#free-trial-pop").click(function(event) {
  		$(this).fadeOut("slow");
	});
</script>

<style>
#free-trial-pop {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    display: none;
    z-index: 9999;
}
#free-trial-pop aside {
    width: 100%;
    max-width: 560px;
    border-radius: 15px;
    margin: auto;
    height: 665px;
    background-color: #fff;
    position: absolute;
    left: 0;
    right: 0;
    top: 50%;
    transform: translate(0, -50%);
}
#free-trial-pop iframe {
    width: 100%;
    height: 100%;
    border-radius: 15px;
}
.close-wrap {
    background-color: #223863;
    position: absolute;
    right: -15px;
    top: -15px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}
.close-wrap img {
    width: 20px;
}
</style>
function reloadcode(){
    	var verify=document.getElementById('safecode');
    	verify.setAttribute('src','https://api.592.us.kg/api?'+Math.random());
    	//这里必须加入随机数不然地址相同无法重新加载
    	}
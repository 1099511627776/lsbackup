function archiveItems(path,count) {
	ls.ajax(path,{
		'records':count
	},function(data){	
		ls.msg.notice(data.message);
		if(data.status != 0) {
			archiveItems(path,count);
		}
	});	
}
let blocksStatus = GUTENTOR_BLOCKS.status;

if( typeof wp.blocks.unregisterBlockType !== "undefined" ){
	Object.keys( blocksStatus ).map( function( key ){
		if(blocksStatus[ key ] !== null) {
			if (blocksStatus[key] === 'disabled') {
				wp.domReady(() => {
					wp.blocks.unregisterBlockType('gutentor/' + key);
				});
			}
		}
	});
}
<?php
class CSBPattern{
	public function __construct(){
		add_action( 'plugins_loaded', [$this, 'onPluginsLoaded'] );
	}

	function onPluginsLoaded(){
		// Register Pattern Category
		if( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'csbPattern',
				array( 'label' => __( 'Content Slider Block', 'content-slider-block' ) )
			);
		}

		// Register Pattern
		if(!empty($this->patternTemplates())){
			foreach($this->patternTemplates() as $template){
				if( function_exists( 'register_block_pattern' ) ) {
					register_block_pattern( $template['name'], array(
							'title'			=> $template['title'],
							'content'		=> $template['content'],
							'description'	=> $template['description'],
							'categories'	=> array( 'csbPattern' ),
							'keywords'		=> $template['keywords'],
							'blockTypes'	=> $template['blockTypes'],
							'viewportWidth'	=> 1200
						)
					);
				}
			}
		}
	}

	// Pattern Templates
	function patternTemplates(){
		return [
			[
				'name'			=> 'csb/pattern-11',
				'title'			=> __( 'Slider General 1', 'content-slider-block' ),
				'description'	=> __( 'Slider General Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"align":"full","cId":"1b6dbf7c-d","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/black-and-brown-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"#000000b3"},"border":{},"position":"","positionHolder":"center center","childPositions":[{"left":39,"top":33.76923076923077},{"left":40,"top":47.84615384615385},{"left":47,"top":57.76923076923077}],"title":"Slide Title 1","titleColor":"#fff","description":"This content area describes slider 1 descriptions/details.","descColor":"#fff","btnText":"Button 1","btnLink":"#","btnColors":{"color":"#fff","bg":"rgba(0, 0, 0, 0)","bgType":"solid"},"btnHovColors":{"color":"rgba(55, 55, 55, 1)","bg":"rgba(255, 255, 255, 1)","bgType":"solid"}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/gray-and-brown-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"#000000b3"},"border":{},"position":"","positionHolder":"center center","childPositions":[{"left":39,"top":33.76923076923077},{"left":40,"top":47.84615384615385},{"left":47,"top":57.76923076923077}],"title":"Slide Title 2","titleColor":"#fff","description":"This content area describes slider 2 descriptions/details.","descColor":"#fff","btnText":"Button 2","btnLink":"#","btnColors":{"color":"#fff","bg":"rgba(0, 0, 0, 0)","bgType":"solid"},"btnHovColors":{"color":"rgba(55, 55, 55, 1)","bg":"rgba(255, 255, 255, 1)","bgType":"solid"}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-forest-near-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"#000000b3"},"border":{},"position":"","positionHolder":"center center","childPositions":[{"left":39,"top":33.76923076923077},{"left":40,"top":47.84615384615385},{"left":47,"top":57.76923076923077}],"title":"Slide Title 3","titleColor":"#fff","description":"This content area describes slider 3 descriptions/details.","descColor":"#fff","btnText":"Button 3","btnLink":"#","btnColors":{"color":"#fff","bg":"rgba(0, 0, 0, 0)","bgType":"solid"},"btnHovColors":{"color":"rgba(55, 55, 55, 1)","bg":"rgba(255, 255, 255, 1)","bgType":"solid"}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-hill.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"#000000b3"},"border":{},"position":"","positionHolder":"center center","childPositions":[{"left":39,"top":33.76923076923077},{"left":40,"top":47.84615384615385},{"left":47,"top":57.76923076923077}],"title":"Slide Title 4","titleColor":"#fff","description":"This content area describes slider 4 descriptions/details.","descColor":"#fff","btnText":"Button 4","btnLink":"#","btnColors":{"color":"#fff","bg":"rgba(0, 0, 0, 0)","bgType":"solid"},"btnHovColors":{"color":"rgba(55, 55, 55, 1)","bg":"rgba(255, 255, 255, 1)","bgType":"solid"}}],"sliderHeight":"650px","pageWidth":"36px","pageHeight":"2px","pageBorder":{"radius":"50%","width":"0px","style":"solid","color":"#0000","side":"all"},"titleTypo":{"fontSize":{"desktop":50,"tablet":40,"mobile":30},"googleFontLink":"https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap","fontFamily":"Oswald","fontCategory":"sans-serif","fontWeight":300,"fontVariant":"300","textTransform":"uppercase","letterSpace":"15px","lineHeight":"135%"},"btnTypo":{"fontSize":{"desktop":12,"tablet":12,"mobile":12},"fontFamily":"Oswald","fontCategory":"sans-serif","fontWeight":400,"fontVariant":400,"textTransform":"uppercase","letterSpace":"4.5px"},"btnPadding":{"vertical":"13px","horizontal":"19px","side":2,"top":"0px","right":"0px","bottom":"0px","left":"0px"},"btnBorder":{"radius":"0px","width":"1px","style":"solid","color":"rgba(225, 225, 225, 1)","side":"all"}} /-->',
			],
			[
				'name'			=> 'csb/pattern-21',
				'title'			=> __( 'Slider Fade 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Fade Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"2a97f31f-6","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/aerial-view-of-a-desert.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/desert-under-purple-sky.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-bushes-on-desert.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/palm-tree-near-white-concrete-building.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"sliderHeight":"450px","effect":"fade","isPage":false,"isTitle":false,"isDesc":false,"isBtn":false} /-->',
			],
			[
				'name'			=> 'csb/pattern-31',
				'title'			=> __( 'Slider Cube 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Cube Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"b4fdbaf1-e","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/orange-petaled-flower.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/pink-flowers.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/red-petal-flower.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":"{}","position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/white-blooming-flower-under-the-tree.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":"{}","position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"sliderWidth":"380px","effect":"cube","isPrevNext":false,"isTitle":false,"isDesc":false,"isBtn":false} /-->',
			],
			[
				'name'			=> 'csb/pattern-41',
				'title'			=> __( 'Slider Creative 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Creative Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"028ee43e-d","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-grass-field.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/wheat-plants.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/white-blooming-flower-under-the-tree.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/white-daisy-on-grass.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"sliderHeight":"450px","effect":"creative","isPage":false,"isTitle":false,"isDesc":false,"isBtn":false} /-->',
			],
			[
				'name'			=> 'csb/pattern-51',
				'title'			=> __( 'Slider Coverflow 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Coverflow Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"cea9c768-3","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/black-and-brown-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/gray-and-brown-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-forest-near-mountain.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/green-hill.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"columns":{"desktop":3,"tablet":2,"mobile":1},"effect":"coverflow","isPrevNext":false,"isTitle":false,"isDesc":false,"isBtn":false} /-->',
			],
			[
				'name'			=> 'csb/pattern-61',
				'title'			=> __( 'Slider Flip 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Flip Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"d3369df6-4","slides":[{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/orange-petaled-flower.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/pink-flowers.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/red-petal-flower.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"type":"image","image":{"id":null,"url":"https://csb.bplugins.com/wp-content/pattern/white-blooming-flower-under-the-tree.jpg","alt":"","title":""},"position":"center center","attachment":"initial","repeat":"no-repeat","size":"cover","overlayColor":"rgba(0, 0, 0, 0)"},"border":{},"position":"","positionHolder":"center center","childPositions":[],"title":"","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"sliderWidth":"480px","effect":"flip","isPage":false,"sliderPadding":{"vertical":"0px","horizontal":"50px","side":2},"pageColor":"rgba(69, 39, 164, 1)","prevNextColor":"rgba(69, 39, 164, 1)","isTitle":false,"isDesc":false,"isBtn":false} /-->',
			],
			[
				'name'			=> 'csb/pattern-71',
				'title'			=> __( 'Slider Cards 1', 'content-slider-block' ),
				'description'	=> __( 'Slider Cards Design', 'content-slider-block' ),
				'keywords'		=> [ 'slider', 'carousel', 'image slider' ],
				'blockTypes'	=> array( 'csb/content-slider-block' ),
				'content'		=> '<!-- wp:csb/content-slider-block {"cId":"d7690f92-8","slides":[{"background":{"color":"rgba(255, 0, 0, 1)","type":"solid"},"border":{"radius":"20px"},"position":"","positionHolder":"center center","childPositions":[{"top":45.875,"left":37.833}],"title":"Slide 1","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"color":"rgba(255, 69, 0, 1)","type":"solid"},"border":{"radius":"20px"},"position":"","positionHolder":"center center","childPositions":[{"top":45.875,"left":37.833}],"title":"Slide 2","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"color":"rgba(0, 128, 0, 1)","type":"solid"},"border":{"radius":"20px"},"position":"","positionHolder":"center center","childPositions":[{"top":45.875,"left":37.833}],"title":"Slide 3","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}},{"background":{"color":"rgba(0, 0, 255, 1)","type":"solid"},"border":{"radius":"20px"},"position":"","positionHolder":"center center","childPositions":[{"top":45.875,"left":37.833}],"title":"Slide 4","titleColor":"#fff","description":"","descColor":"#fff","btnText":"","btnLink":"","btnColors":{},"btnHovColors":{}}],"columns":{"desktop":1,"tablet":1,"mobile":1},"sliderWidth":"400px","sliderHeight":"500px","effect":"cards","isPage":false,"isPrevNext":false,"sliderPadding":{"vertical":"50px","horizontal":"50px"},"isDesc":false,"isBtn":false} /-->',
			]
		];
	}
}
new CSBPattern();
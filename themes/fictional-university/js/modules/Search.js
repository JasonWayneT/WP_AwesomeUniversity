class Search{
    //1. Init
    constructor(){
        this.addSearchHTML();
        this.resultsDiv = document.querySelector('#search-overlay__results');
        this.openButton = document.querySelector('#mainSearchBtn');
        this.closeButton = document.querySelector('.search-overlay__close');
        this.searchOverlay = document.querySelector('.search-overlay');
        this.searchField = document.querySelector('#search-term');
        this.inputFields = document.querySelectorAll('input')
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer;
        this.previousValue;

        this.events();
        
    }

    // 2. Events
    events(){
        this.openButton.addEventListener('click', this.openOverlay.bind(this));
        this.closeButton.addEventListener('click', this.closeOverlay.bind(this));
        this.searchField.addEventListener('keydown', this.typingLogic.bind(this));
        document.addEventListener('keyup', this.keyPreeDispatcher.bind(this));
        
    }
    

    // 3. Methods
    typingLogic(){
        if(this.searchField.value != this.previousValue){
        clearTimeout(this.typingTimer);
        if(this.searchField.value){
            if(!this.isSpinnerVisible){
                this.resultsDiv.innerHTML='<div class="spinner-loader"></div>';
                this.isSpinnerVisible = true;
            }
            
            this.typingTimer = setTimeout( this.getResults.bind(this) ,750)
        }else{
            this.resultsDiv.innerHTML='';
            this.isSpinnerVisible = false;
        }
       
    }
        this.previousValue = this.searchField.value;  
    }

    getResults(){
        const postsRequest = fetch(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.value )
            .then(function(response){
                    return response.json();
                });
        const pagesRequest = fetch(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.value )
        .then(function(response2){
                return response2.json();
            });
        let combinedResponse = [];
        Promise.all([postsRequest, pagesRequest])
             .then(combinedResponse => {
                 let fullResponse= combinedResponse[0].concat(combinedResponse[1])
                 console.log(fullResponse);
                 this.resultsDiv.innerHTML=`
                 <h2 class="search-overlay__section-title">General Information</h2>
                 ${fullResponse.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                   ${fullResponse.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
                 ${fullResponse.length ? '</ul>' : ''}
               `;
               this.isSpinnerVisible = false;
             });// end promis.all .then
   
          } // end get results
       
        

       
    

    openOverlay(){
        this.searchOverlay.classList.add('search-overlay--active');
        document.body.classList.add('body-no-scroll');
        this.searchField.value = '';
        setTimeout(()=> this.searchField.focus(),300)
        this.isOverlayOpen = true;
    }

    closeOverlay(){
        this.searchOverlay.classList.remove('search-overlay--active');
        document.body.classList.remove('body-no-scroll');
        this.isOverlayOpen = false;
    }

    keyPreeDispatcher(event){
        if(event.keyCode === 83 && !this.isOverlayOpen && this.searchField == document.activeElement ){
            this.openOverlay();
        }
        if(event.keyCode === 27 && this.isOverlayOpen ){
            this.closeOverlay();
        }
        
        
        
    }
    addSearchHTML(){
      let  div = document.createElement('div')
        div.classList.add('search-overlay')
        div.innerHTML= `
        
	<div class="search-overlay__top">
		<div class="container">
			<i class="fa fa-search search-overlay__icon" aria-hidden='true' id="searchButton"></i>
			<input type="text" class='search-term' placeholder='What are you looking for?' id='search-term'>
			<i class="fa fa-window-close search-overlay__close" aria-hidden='true'></i>
		</div>
	</div>
	<div class="container">
			<div id='search-overlay__results'></div>
	</div>

        `
        document.body.append(div)
    }

    
    
}

export default Search;



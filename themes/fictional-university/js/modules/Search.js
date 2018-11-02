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
         fetch(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.value )
        .then((response) => { return response.json();})
        .then((mainQuery)=> {
            console.log(mainQuery);
            
            this.resultsDiv.innerHTML=`
            <div class='row'>
                <div class='one-third'>
                     <h2 class="search-overlay__section-title">General Information</h2>
                        ${mainQuery.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                        ${mainQuery.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.type == 'post' ? `by ${item.author}` : ''} </li>`).join('')}
                        ${mainQuery.generalInfo.length ? '</ul>' : ''}
                </div>
                <div class='one-third'>
                     <h2 class="search-overlay__section-title">Programs </h2>
                        ${mainQuery.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs"> View all programs.</a></p>`}
                        ${mainQuery.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
                        ${mainQuery.programs.length ? '</ul>' : ''}
                     <h2 class="search-overlay__section-title">Professors </h2>
                        ${mainQuery.professors.length ? '<ul class="professor-cards">' : '<p>No professors matches that search.</p>'}
                        ${mainQuery.professors.map(item => `
                                            <li class="professor-card__list-item">
                                <a class="professor-card" href="${item.permalink}">
                                <img class="professor-card__image" src="${item.thumbnail}">
                                <span class="professor-card__name">${item.title}</span>
                                </a>
                            </li>
                        `).join('')}
                        ${mainQuery.professors.length ? '</ul>' : ''}
                </div>
                <div class='one-third'>
                    <h2 class="search-overlay__section-title">Campuses</h2>
                        ${mainQuery.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses"> View all campuses.</a></p></p>`}
                        ${mainQuery.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a> </li>`).join('')}
                        ${mainQuery.campuses.length ? '</ul>' : ''}

                    <h2 class="search-overlay__section-title">Events</h2>
                        ${mainQuery.events.length ? '' : `<p>No events match that search. <a href="${universityData.root_url}/events" View All Events</a> </p>`}
                        ${mainQuery.events.map(item => `
                            <div class="event-summary">
                                <a class="event-summary__date event-summary__date--beige ?>  t-center" href="${item.permalink}">
                                    <span class="event-summary__month">${item.month}</span>
                                    <span class="event-summary__day">${item.day}</span>  
                                </a>
                                <div class="event-summary__content">
                                    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title} </a></h5>
                                    <p>${item.excerpt} <a href="${item.permalink}" class="nu gray">Read more</a></p>
                                </div>
                            </div>

                        
                        `).join('')}
                        
                </div>
            </div>
            `
        } )
        .catch(()=>{this.resultsDiv.innerHTML= '<p> Sorry there was an error. Please try again.</p>'});

        this.isSpinnerVisible = false;
    } 


    openOverlay(e){
        this.searchOverlay.classList.add('search-overlay--active');
        document.body.classList.add('body-no-scroll');
        this.searchField.value = '';
        setTimeout(()=> this.searchField.focus(),300)
        this.isOverlayOpen = true;
        // prevents behavior of <a>
        e.preventDefault();
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



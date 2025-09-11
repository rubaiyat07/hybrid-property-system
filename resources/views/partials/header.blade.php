<nav class="navbar"> 
    <div class="nav-container"> 
        <div class="logo"><a href="{{ route('public_pages.welcome') }}">HybridEstate</a></div> 
        <ul class="nav-links"> 
            <li><a href="{{ route('public_pages.welcome') }}">Home</a></li> 
            <li><a href="#features">Features</a></li> 
            <li><a href="#rent">House Rent</a></li> 
            <li><a href="#customers">Customers</a></li> 
            <li><a href="#about">About</a></li> 
            <li><a href="#contact">Contact</a></li> 
            </ul> 
            
            {{-- <a href="#rent" class="cta-btn">Get Started</a> --}} 
            <div> 
            <a href="{{ route('login') }}" class="cta-btn">Log in</a> 
            <a href="{{ route('register') }}" class="cta-btn">Sign up</a> 
            </div> 
    </div> 
</nav>
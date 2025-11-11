<section class="relative w-full h-[90vh] overflow-hidden bg-gray-800">
  <!-- Dark overlay -->
  <div class="absolute inset-0 bg-black/50"></div>

  <!-- Hero content -->
  <div class="relative z-10 flex flex-col justify-center items-center md:items-start h-full px-6 md:px-16 text-center md:text-left max-w-4xl mx-auto md:mx-0">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-snug mb-2">
      We are helping startups to
    </h1>
 
   <h1 class="small-hero-h1 text-3xl sm:text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text 
    bg-gradient-to-r 
    from-[#FF00FF] 
    via-[#8A2BE2] 
    to-[#00BFFF] 
    mb-6">
    	Grow Your Business
	</h1>

    <p class="text-gray-400 text-lg md:text-xl font-light max-w-xl mb-8 leading-relaxed">
    	We have over 
    	<span class="text-xl md:text-2xl font-bold text-blue-700">13+ years</span> 
    	of corporate and consulting experience with top firms. Our network includes experienced chartered accountants, company secretaries, lawyers, cost 					accountants, and many more.
	</p>

    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
      <a href="#" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md transition transform hover:-translate-y-1">
        Get Started
      </a>
      <a href="#services" class="px-6 py-3 bg-white/20 hover:bg-white/30 border border-white text-white font-semibold rounded-lg transition transform hover:-translate-y-1">
        Our Services
      </a>
    </div>
  </div>

  <!-- Bottom fade -->
  <!-- <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white/20 to-transparent"></div> -->

  <!-- Ripple Container -->
  <div id="ripple-container" class="absolute inset-0 pointer-events-none"></div>
</section>

<style>
    
/* For Hero Section */
/* Lightened background */
 .bg-gray-800 {
     background-color: #2d2d2d;
}
/* Ripple effect styling */
 #ripple-container {
     position: absolute;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     pointer-events: none;
}
 .ripple {
     position: absolute;
     border-radius: 50%;
     background: rgba(255, 255, 255, 0.6);
     animation: ripple-animation 1.5s ease-out forwards;
     pointer-events: none;
}
/* Ripple animation */
 @keyframes ripple-animation {
     0% {
         transform: scale(0);
         opacity: 0.6;
    }
     100% {
         transform: scale(4);
         opacity: 0;
    }
}
/* Darkish white ripple effect */
 .ripple-darkish-white {
     background: radial-gradient(circle, rgba(230, 230, 230, 0.5), rgba(180, 180, 180, 0.4));
     animation: ripple-animation 1.5s ease-out forwards;
}
/* Mobile-Specific Font Size Adjustments */
 @media (max-width: 767px) {
     .text-3xl{
         line-height: 3.4rem;
         font-size: 3rem;
    }
     .small-hero-h1 {
         font-size: 3rem;
    }
     .text-4xl {
         font-size: 4rem;
    }
     .text-5xl {
         font-size: 5rem;
    }
     .text-6xl {
         font-size: 6rem;
    }
     .text-lg {
         font-size: 1.2rem;
    }
     .text-xl {
         font-size: 1.5rem;
    }
}
    
</style>


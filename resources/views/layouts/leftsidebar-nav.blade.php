   <nav id="sidebar" aria-label="Main Navigation">
       <!-- Side Header -->
       <div class="content-header">
           <!-- Logo -->
           <a class="font-semibold text-dual" href="/">
               <span class="smini-visible">
                   <i class="fa fa-circle-notch text-primary"></i>
               </span>
               <span class="smini-hide fs-5 tracking-wider">Sud<span class="fw-normal text-danger">Energy</span></span>
           </a>
           <!-- END Logo -->

           <!-- Extra -->
           <div>
               <!-- Dark Mode -->
               <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
              <form action="{{ route('erp.change-mode') }}" method="post" style="display: inline-block;">
                @csrf
                <input type="hidden" value="@if (Auth::user()->mode=="light")
                dark
                @else
                light
                @endif" name="mode" />
                <button type="submit" class="btn btn-sm btn-alt-secondary">

                    <i class="far fa-moon"></i>
                </button>
            </form>
               <!-- END Dark Mode -->

       
           </div>
           <!-- END Extra -->
       </div>
       <!-- END Side Header -->

       <!-- Sidebar Scrolling -->
       <div class="js-sidebar-scroll">
           <!-- Side Navigation -->
           @include('layouts.backend.sidebar')

           <!-- END Side Navigation -->
       </div>
       <!-- END Sidebar Scrolling -->
   </nav>

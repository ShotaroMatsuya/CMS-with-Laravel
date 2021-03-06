<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>
        @yield('title')
    </title>

    <!-- Styles -->
  <link href="{{asset('css/page.min.css')}}" rel="stylesheet">
  <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <!-- Favicons -->
  <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon.png')}}">
    <link rel="icon" href="{{asset('img/favicon.png')}}">
  </head>

  <body>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-stick-dark" data-navbar="sticky">
      <div class="container">

        <div class="navbar-left">
          <button class="navbar-toggler" type="button">&#9776;</button>
        <a class="navbar-brand" href="{{route('welcome')}}">
          <img class="logo-dark" width="100px" src="{{asset('img/logo-dark.png')}}" alt="logo">
            <img class="logo-light" width="100px" src="{{asset('img/logo-light.png')}}" alt="logo">
          </a>
        </div>

        <section class="navbar-mobile">
          <span class="navbar-divider d-mobile-none"></span>

          <ul class="nav nav-navbar">
            <li class="nav-item">
            <a class="nav-link" href="{{route('welcome')}}">LaraBlog</span></a>

            </li>



            <li class="nav-item">
              <a class="nav-link" href="#">カテゴリ一覧 <span class="arrow"></span></a>
              <nav class="nav">
                  @foreach ($categories as $category)
              <a class="nav-link" href="{{route('blog.category',$category->id)}}">{{$category->name}}</a>
                  @endforeach
              </nav>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">タグ一覧 <span class="arrow"></span></a>
              <nav class="nav">
                @foreach ($tags as $tag)
              <a class="nav-link" href="{{route('blog.tag',$tag->id)}}">{{$tag->name}}</a>
                @endforeach
            </nav>
            </li>

          </ul>
        </section>

    <a class="btn btn-xs btn-round btn-success" href="{{route('login')}}">Login</a>

      </div>
    </nav><!-- /.navbar -->


    <!-- Header -->
@yield('header')
    <!-- Main Content -->
 @yield('content')
    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row gap-y align-items-center">

          <div class="col-6 col-lg-3">
            <a href="/"><img src="{{asset('img/logo-dark.png')}}" alt="logo"></a>
          </div>

          <div class="col-6 col-lg-3 text-right order-lg-last">
            <div class="social">
              <a class="social-facebook" href="https://www.facebook.com/thethemeio"><i class="fa fa-facebook"></i></a>
              <a class="social-twitter" href="https://twitter.com/thethemeio"><i class="fa fa-twitter"></i></a>
              <a class="social-instagram" href="https://www.instagram.com/thethemeio/"><i class="fa fa-instagram"></i></a>
              <a class="social-dribbble" href="https://dribbble.com/thethemeio"><i class="fa fa-dribbble"></i></a>
            </div>
          </div>



        </div>
      </div>
    </footer><!-- /.footer -->


    <!-- Scripts -->
<script src="{{asset('js/page.min.js')}}"></script>
<script src="{{asset('js/script.js')}}"></script>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f8dc9e9223db3b5"></script>


  </body>
</html>

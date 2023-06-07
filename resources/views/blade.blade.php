@extends('app')

@section('no-inertia')
    <div class="page">
        <header class="page-header">
            <div class="container">
                <div class="page-header__content">
                    <a href="/" class="page-header__logo">
                        <img src="{{ asset('img/svg/logo.svg') }}" alt="">
                    </a>
                    <nav class="nav nav--main page-header__nav">
                        <ul class="nav__list">
                            <li class="nav__item">
                                <a href="/" class="nav__link">
                                    Home
                                </a>
                            </li>
                            <li class="nav__item">
                                <a href="/about" class="nav__link">
                                    About
                                </a>
                            </li>
                            <li class="nav__item">
                                <a href="/blade" class="nav__link">
                                    Blade
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <main>
            <section>
                <div class="container">
                    <h1>Blade</h1>
                    <p>This page is rendered the traditional way, with a Blade template.</p>
                </div>
            </section>
        </main>
        <footer class="page-footer">
            <div class="container">
                &copy; Statik
            </div>
        </footer>
    </div>
@endsection

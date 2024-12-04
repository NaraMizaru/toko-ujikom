<nav class="nav bg-dark">
  <a href="" class="nav-link text-white active" aria-current="page" href="#">MBShop</a>
  <a href="{{ route('index.home') }}" class="nav-link text-white">Home</a>
  <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
  {{-- <a href="" class="nav-link text-white">Produk</a> --}}
  <a href="{{ route('index.keranjang') }}" class="nav-link text-white">Keranjang</a>
  <a href="{{ route('index.summary') }}" class="nav-link text-white">Summary</a>
  <a href="{{ route('logout') }}" class="nav-link text-white">Logout</a>
</nav>
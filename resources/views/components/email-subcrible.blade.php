<form action="{{ route('email.subcrible')}}" method="post">
    {!! csrf_field() !!}
    <input type="text" name="email" class="search" required="" placeholder="thuytrangnguyen@gmail.com"/>
    <button class="button-style" type="submit" value="">Đăng k&yacute;</button>
</form>
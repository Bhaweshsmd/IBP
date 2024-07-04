<div class="sortby-section court-sortby-section">
	<div class="sorting-info">
		<div class="row d-flex align-items-center">
			<div class="col-xl-12 col-lg-12 col-sm-12 col-12">
				<div class="coach-court-list">
					<ul class="nav">
					   <li><a  class="@if($type==null) active @endif" href="{{route('user-wallet')}}">All Transactions</a></li>
					    <li><a class="@if($type=='deposit') active @endif" href="{{route('user-wallet','deposit')}}">Deposit</a></li>
						<li><a class="@if($type=='withdraw') active @endif" href="{{route('user-wallet','withdraw')}}">Withdraw</a></li>
						<li><a class="@if($type=='refund') active @endif" href="{{route('user-wallet','refund')}}">Refund</a></li>
						<li><a class="@if($type=='purchase') active @endif" href="{{route('user-wallet','purchase')}}">Booking Payments</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
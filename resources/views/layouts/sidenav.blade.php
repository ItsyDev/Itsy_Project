<div class="sidebar transition overlay-scrollbars">
	<div class="logo d-block py-3 text-center">
		<h3 style="font-weight: 700;" class="mb-0">Administrator</h3>
		<p>Halo, Rizky </p>
		{{--  {{ $user_login->user_name }}  --}}
	</div>
	<div class="sidebar-items" style="margin-top: -20px;">
		<hr style="border: 1px solid white;">
		<div class="accordion" id="sidebar-items">
			<ul>
				<p class="menu">Apps</p>
				<li>
					<a href="#" onclick="navigateTo('dashboard')" class="items @if($title == "Dashbaord") active @endif">
						<i class="fa fa-tachometer-alt"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<p class="menu">Point Of Sales</p>
				<li>
					<a href="#" onclick="navigateTo('administrator')" class="items @if($title == "Administration") active @endif">
							<i class="fa fa-users"></i>
							<span>Administration</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="navigateTo('warehouse')" class="items @if($title == "Warehouse Management") active @endif">
							<i class="fas fa-warehouse"></i>
							<span>Warehouse</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="navigateTo('supplier')" class="items @if($title == "Supplier Management") active @endif">
							<i class="fa fa-parachute-box"></i>
							<span>Supplier</span>
					</a>
				</li>
				<li>
					<a href="#" onclick="navigateTo('customer')" class="items @if($title == "Customer Management") active @endif">
							<i class="fa fa-users"></i>
							<span>Customer</span>
					</a>
				</li>
			<li id="headingThree">
					<a href="onclick();" class="submenu-items" data-toggle="collapse" data-target="#forms"
							aria-expanded="true" aria-controls="forms">
							<i class="fas fa-boxes"></i>
							<span>Barang</span>
							<i class="fas la-angle-right"></i>
					</a>
			</li>
			<div id="forms" class="collapse submenu" aria-labelledby="headingThree"
					data-parent="#sidebar-items">
					<ul>
							<li>
									<a href="#" onclick="navigateTo('category-product')">Category</a>
							</li>
							<li>
									<a href="#" onclick="navigateTo('barang-masuk')">Barang Masuk</a>
							</li>
							<li>
									<a href="#">Barang Keluar</a>
							</li>
					</ul>
			</div>
			</ul>
		</div>
	</div>
</div>
<div class="sidebar-overlay"></div>
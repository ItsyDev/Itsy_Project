<div class="topbar transition">
	<div class="bars">
		<button type="button" class="btn transition" id="sidebar-toggle">
			<i class="las la-bars"></i>
		</button>
	</div>
	<div class="menu">

		<ul>

			<li>
				<a href="notifications.html" class="transition">
					<i class="las la-bell"></i>
					<span class="badge badge-danger notif">5</span>
				</a>
			</li>

			<li>
				<div class="dropdown">
					<div class="dropdown-toggle" id="dropdownProfile" data-toggle="dropdown" aria-haspopup="true"
						aria-expanded="false">
						<img src="assets/images/avatar/avatar-2.png" alt="Profile">
					</div>
					<div class="dropdown-menu" aria-labelledby="dropdownProfile">

						<a class="dropdown-item" href="#" onclick="navigateTo('{{ 'user/'.encrypt_url(session('user_id')) }}')">
							<i class="las la-user mr-2"></i> My Profile
						</a>

						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#" onclick="showModal('isLogout')">
							<i class="las la-sign-out-alt mr-2"></i> Sign Out
						</a>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
<body>
<table class="error_page">
    <tbody>
        <tr>
            <td>
                <h1>403</h1>
                <div>Ошибка: Доступ запрещен</div>
                <div>{{ $exception->getMessage() }}</div>
                <button href="{{secure_url('/')}}" class="btn_svg">Вернуться на главную</button>
            </td>
        </tr>
    </tbody>
</table>

<style>
	body {
		background: url({{ secure_asset('images/bg_404.jpg', '404')}}) no-repeat center bottom;
	}
	.error_page {
	width: 100%; height: 100%; font-family: Arial, sans-serif; color:#17568e; font-size: 20px;
}
.error_page td {
	vertical-align: top; 
	text-align: center;
	padding-top: 50px;
}
.error_page h1 {
	font-size: 170px;
	margin: 0;
	line-height: 1;
}
 .btn_svg {
	margin-top: 30px;
	background: transparent;
	border: none;
	color: #fff !important;
	display: inline-block;
	text-decoration: none !important;
	font-size: 13px;
	text-transform: uppercase;
	font-weight: bold;
	padding: 24px 25px 6px;
	line-height: 16px;
	text-align: center;
	width: 250px;
	min-height: 60px;
	background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjczIDc1LjMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI3MyA3NS4zOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4uc3Qwe2ZpbGw6IzMwREFGRjt9PC9zdHlsZT48cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjEwLjcsNzEgMCwyMCAyNzMsMCAyNTcuMyw3NS4zICIvPjwvc3ZnPg==');
	background-size: 100% 100%;
	background-repeat: no-repeat;
	transition: .2s;
}
.btn_svg:hover {
	background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMjczIDc1LjMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI3MyA3NS4zOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4uc3Qwe2ZpbGw6IzE3NTY4RTt9PC9zdHlsZT48cG9seWdvbiBjbGFzcz0ic3QwIiBwb2ludHM9IjEwLjcsNzEgMCwyMCAyNzMsMCAyNTcuMyw3NS4zICIvPjwvc3ZnPg==');
} 
</style>
</body>

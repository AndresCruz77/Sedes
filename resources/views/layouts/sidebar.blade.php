<div>
    <menu-user-component 
        :ruta='{{ json_encode(Request::path()) }}'
        :user='{{ json_encode(Auth::user()) }}'
        :url='{{ json_encode(url("/")) }}'
        >
    </menu-user-component>
</div>
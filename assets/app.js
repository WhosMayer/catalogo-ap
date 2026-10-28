(() => {
    const normalizar = value => value.trim().normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    const ciudad = document.getElementById('ciudad');
    const fuente = document.getElementById('datos-ciudades');
    if (ciudad && fuente) {
        const ciudades = JSON.parse(fuente.textContent);
        const pais = document.getElementById('pais');
        const completar = () => {
            const clave = Object.keys(ciudades).find(c => normalizar(c) === normalizar(ciudad.value));
            pais.value = clave ? ciudades[clave] : '';
        };
        ciudad.addEventListener('input', completar);
        completar();
    }
    document.querySelector('[aria-invalid="true"]')?.focus();
    const tarjetas = [...document.querySelectorAll('.product-card')];
    const buscar = document.getElementById('buscar-productos');
    const filtros = [...document.querySelectorAll('.filter-chip')];
    let categoria = '';
    function filtrar() {
        const texto = normalizar(buscar.value);
        let visibles = 0;
        tarjetas.forEach(tarjeta => {
            const coincide = (!categoria || tarjeta.dataset.categoria === categoria) && normalizar(tarjeta.dataset.busqueda).includes(texto);
            tarjeta.hidden = !coincide;
            if (coincide) visibles++;
        });
        document.getElementById('sin-resultados').hidden = visibles !== 0;
        document.getElementById('resultado-filtro').textContent = `${visibles} productos encontrados`;
    }
    if (buscar) {
        buscar.addEventListener('input', filtrar);
        filtros.forEach(boton => boton.addEventListener('click', () => {
            categoria = boton.dataset.categoria;
            filtros.forEach(f => { f.classList.toggle('active', f === boton); f.setAttribute('aria-pressed', String(f === boton)); });
            filtrar();
        }));
    }
})();

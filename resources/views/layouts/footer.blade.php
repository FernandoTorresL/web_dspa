<footer>
    <div class="col-md-16 text-center">
        <div class="row ">
            <div class="small col-md-3">
                <strong>
                    <p>
                        Conmutador
                            {{ $contacto_conm2 }}
                    </p>
                    (ext. {{ $contacto_ext }})
                </strong>
            </div>

            <div class="small col-md-6">
                <strong>
                    <p>
                        {{ $contacto_dir }}
                    </p>
                    {{ $contacto_div }}
                </strong>
            </div>

            <div class="small col-md-3">
                <strong>
                    <p>
                        ¿Necesitas ayuda?
                    </p>
                    <a href="mailto: {{ $contacto_mail }}">Contáctanos aquí</a>
                </strong>
            </div>

        </div>
    </div>
    <br>
</footer>

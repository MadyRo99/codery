@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')

    <tr class="content-text">
        <tr>
            <td width="100%" height="20"></td>
        </tr>
        <tr>
            <td class="title">
                <h1>Salutare!</h1>
            </td>
        </tr>
        <tr>
            <td width="100%" height="15"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <p>Mai ai un singur pas de făcut pentru a te alătura comunității Codery.</p>
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <p>Apasă pe butonul de mai jos pentru a confirma că vrei să primești lunar un email cu Newsletter-ul nostru conținând articole recente.</p>
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <p>Nu îți face griji... Nu facem spam și vei primi doar cele mai bune articole &#128522;</p>
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td class="confirm-button">
                @include('beautymail::templates.minty.button', ['text' => 'Confirmă', 'link' => '#'])
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
    </tr>

    <tr>
        <td class="paragraph bottom-notice">
            <p>Dacă nu tu ai solicitat să te abonezi la Newsletter te rugăm să ignori acest email.</p>
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop

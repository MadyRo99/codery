@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')

    <div class="content-text">
        <tr>
            <td width="100%" height="20"></td>
        </tr>
        <tr>
            <td class="title">
                <h1>Bine ai venit în comunitatea Codery!</h1>
            </td>
        </tr>
        <tr>
            <td width="100%" height="15"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <p>Felicitari! Ai confirmat cu succes abonarea ta la Newsletter.</p>
            </td>
        </tr>
        <tr>
            <td width="100%" height="25"></td>
        </tr>
        <tr>
            <td class="paragraph">
                <p>De acum vei primi lunar un email cu cele mai bune articole ale lunii și vei fii astfel la curent cu ultimele noutăți și informații din lumea IT. &#128079;</p>
            </td>
        </tr>
    </div>

    <tr>
        <td width="100%" height="25"></td>
    </tr>

    <tr>
        <td class="paragraph bottom-notice">
            <p>Apasă <a href="http://localhost:8000/deleteNewsletter/{{ $token }}" class="unsubscribe">aici</a> dacă dorești să te dezabonezi de la Newsletter.</p>
        </td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop

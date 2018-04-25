<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Notebook</title>

		<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>
    <body>

        <h2>Записная книжка</h2>

        <br/>
		
		@if (@isset($modifyRecId))
			<form method="post" action="/modify" accept-charset="UTF-8">
				<fieldset>
					<legend>Редактирование записи {{ $fio }}</legend>
								
				<label>ФИО:
				<input type="text" name="fio" placeholder="ФИО" value="{{ $fio }}"></input>
				</label>
				<label>Номер телефона:
				<input type="text" name="phonenum" placeholder="+7 (900) 123-45-67" pattern="\+7\s?[\(]{0,1}9[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}" value="{{ $phonenum }}"></input><br/><br/>
				</label>
				<input type="hidden" name="recId" value="{{ $modifyRecId }}" />
				<input type="submit" name="modifyrecord" value="Редактировать" />

				</fieldset>
				
				{{csrf_field()}}
			</form>
		@else
			<form method="post" action="/add" accept-charset="UTF-8">
				<label>ФИО:
				<input type="text" name="fio" placeholder="ФИО"></input>
				</label>
				<label>Номер телефона:
				<input type="text" name="phonenum" placeholder="+7 (900) 123-45-67" pattern="\+7\s?[\(]{0,1}9[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}"></input><br/><br/>
				</label>
				<input type="submit" name="addrecord" value="Добавить" />

				{{csrf_field()}}
			</form>
		@endif

		@if(count($phoneRecs) > 0)
		<br/>	
		<table name="Phones">
				<tr>
					<th>ФИО</th>
					<th>Номер телефона</th>
                    <th>Операции</th>
				</tr>
				
				@foreach ($phoneRecs as $phoneRec)	
					<tr>
						<td> {{ $phoneRec->FIO }} </td>
						<td> {{ $phoneRec->phonenum }} </td>
						<td>
							<a href="?modifyRecId={{ $phoneRec->id }}&fio={{ $phoneRec->FIO }}&phonenum={{ $phoneRec->phonenum }}" style="color: green">Изменить</a> 
						
                            <form method="post" class="frm-link" action="/delete">
                                <button type="submit" name="delete" class="btn-link">Удалить</button>
                                <input type="hidden" name="recId" value="{{ $phoneRec->id }}"/>

                                {{ csrf_field() }}

                            </form>
						
						</td>
					</tr>
				@endforeach
				
			</table>
		@else	
			<p>Записная книжка пуста</p>
		@endif
				
        <br/>
        <br/>

	</body>
</html>

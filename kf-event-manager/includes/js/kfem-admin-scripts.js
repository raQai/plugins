function kfem_checkboxDivDisplay( ID, box ) {
	var chbox = document.getElementById( ID );
	var container = document.getElementById( box );
	var items = document.getElementsByClassName( 'kf-em-ticket-item' );
	
	if ( chbox.checked ) {
		for (var i = 0; i < items.length; i++) {
			items[i].getElementsByTagName('input')[0].required = true;
		}
		container.style.display = 'block';
	} else {
		for (var i = 0; i < items.length; i++) {
			items[i].getElementsByTagName('input')[0].required = false;
		}
		container.style.display = 'none';
	}
}

function kfem_changeLabelText( ID, string ) {
	document.getElementById( ID ).innerHTML = string;
}

function kfem_createBookingString() {
	var bookingStrings = new Array();
	var tickets, ticket;
	
	tickets = document.getElementsByClassName( 'kf-em-ticket-item' );
	for ( var i = 0 ; i < tickets.length; i++) {
		ticket = tickets[i].getElementsByTagName( 'input' );
		if ( ticket[0].value.trim() != '' ) {
			bookingStrings.push( ticket[0].value + ';' + ticket[1].value + ';' + ticket[2].value + ';' + ticket[3].value );		
		}
	}
	
	document.getElementById( 'kf-em-tickets-information' ).value = bookingStrings.join(';$;');
}

function kfem_createTicket() {
	var ID = document.getElementById( 'kf-em-ticket-counter' ).value;
	var itemString = '';
	
	itemString += '<input type="hidden" value="new" />';
	itemString += '<input onChange="kfem_createBookingString();" required placeholder="Name" /> ';
	itemString += '<input onChange="kfem_createBookingString();" placeholder="frei" /> ';
	itemString += '<input onChange="kfem_createBookingString();" placeholder="unbeschränkt"/> ';
	itemString += '<input onClick="kfem_deleteTicket(\'kf-em-ticket-item['+ ID +']\'); kfem_createBookingString()" value="&cross;" type="button" class="button button-small button-primary" />';

	var div = document.createElement('div');
	div.id = 'kf-em-ticket-item[' + ID + ']';
	div.className = 'kf-em-ticket-item';
	div.innerHTML = itemString;
	document.getElementById( 'kf-em-tickets' ).appendChild( div );
	document.getElementById( 'kf-em-ticket-counter' ).value = ++ID;
}

function kfem_deleteTicket( ID ) {
	var e = document.getElementById( ID );
	if ( confirm( 'Disziplin "' + e.getElementsByTagName('input')[1].value + '" wirklich löschen?' ) ) {
		e.outerHTML = '';
		delete e;
	}
}
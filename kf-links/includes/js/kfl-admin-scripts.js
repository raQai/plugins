function kfl_checkboxDivDisplay( ID, box ) {
	var chbox = document.getElementById( ID );
	var container = document.getElementById( box );
	var items = document.getElementsByClassName( 'kf-links-item' );
	
	if ( chbox.checked ) {
		for (var i = 0; i < items.length; i++) {
			items[i].getElementsByTagName('input')[0].required = true;
			items[i].getElementsByTagName('input')[1].required = true;
		}
		container.style.display = 'block';
	} else {
		for (var i = 0; i < items.length; i++) {
			items[i].getElementsByTagName('input')[0].required = false;
			items[i].getElementsByTagName('input')[1].required = false;
		}
		container.style.display = 'none';
	}
}

function kfl_creaetLinksString() {
	var a, s, x, info, items;
	s = '';
	x = document.getElementById( 'kf-links-checkbox' );
	
	if ( x.checked ) {
		a = ['1'];

		a.push( document.getElementById( 'kf-links-title' ).value.trim() );
		items = document.getElementsByClassName( 'kf-links-item' );
		if ( items.length > 0 ) {
			for ( var i = 0 ; i < items.length; i++ ) {
				info = items[i].getElementsByTagName( 'input' );
				if ( info[0].value.trim() != '' ) {
					a.push( info[0].value.trim() + ';?;' + info[1].value );		
				}
			}
			s = a.join(';$;');
		}	
	}
	document.getElementById( 'kf-links-information' ).value = s;
}

function kfl_createLinkItem() {
	var ID = document.getElementById( 'kf-links-counter' ).value;
	var itemString = '';

	itemString += '<input onChange="kfl_creaetLinksString();" required placeholder="Name" /> ';
	itemString += '<input onChange="kfl_creaetLinksString();" required placeholder="http://..." /> ';
	itemString += '<input onClick="kfl_deleteLinkItem( \'kf-links-item[' + ID + ']\' ); kfl_creaetLinksString();" value="&cross;" type="button" class="button button-small button-primary" />';

	var div = document.createElement('div');
	div.id = 'kf-links-item[' + ID + ']';
	div.className = 'kf-links-item';
	div.innerHTML = itemString;
	document.getElementById( 'kf-links-items' ).appendChild( div );
	document.getElementById( 'kf-links-counter' ).value = ++ID;
}

function kfl_deleteLink( ID ) {
	var e = document.getElementById( ID );
	if ( confirm( 'Link "' + e.getElementsByTagName('input')[0].value + '" wirklich löschen?' ) ) {
		e.outerHTML = '';
		delete e;
	}
}

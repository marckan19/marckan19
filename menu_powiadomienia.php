<?php
if ($row_zalogowany['header'] == 'n'){
	echo '<a href="powiadomienia_zwyzki.php"><button>Arbeitsbühnen</button></a> '; 
	echo '<a href="powiadomienia_noclegi.php"><button>Unterkunft</button></a> '; 
	echo '<a href="powiadomienia_budowy.php"><button>Bauvorhaben</button></a> '; 
	echo '<a href="powiadomienia_wyjazdy.php"><button>Baustellen</button></a> '; 
	echo '<a href="powiadomienia_oferty.php"><button>Angebote</button></a> '; 
	echo '<a href="powiadomienia_usterki.php"><button>Mängel</button></a> '; 
}else{
	echo '<a href="powiadomienia_zwyzki.php"><button>ZWYŻKI</button></a> '; 
	echo '<a href="powiadomienia_noclegi.php"><button>NOCLEGI</button></a> '; 
	echo '<a href="powiadomienia_budowy.php"><button>BUDOWY</button></a> '; 
	echo '<a href="powiadomienia_wyjazdy.php"><button>WYJAZDY</button></a> '; 
	echo '<a href="powiadomienia_oferty.php"><button>OFERTY</button></a> '; 
	echo '<a href="powiadomienia_usterki.php"><button>USTERKI</button></a> '; 
}

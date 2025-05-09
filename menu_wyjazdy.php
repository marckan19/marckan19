<?php
	echo '<br />';
if ($row_zalogowany['header'] == 'n'){
	echo '<a href="tabela_wyjazdy_pracownicy.php"><button>Mitarbeiter</button></a> ';
	echo '<a href="tabela_wyjazdy_budowy.php"><button>Baustellen</button></a> ';
	echo '<a href="tabela_wyjazdy_samochody.php"><button>Dienstwagen</button></a>';
}else{
	echo '<a href="tabela_wyjazdy_pracownicy.php"><button>PRACOWNICY</button></a> ';
	echo '<a href="tabela_wyjazdy_budowy.php"><button>BUDOWY</button></a> ';
	echo '<a href="tabela_wyjazdy_samochody.php"><button>SAMOCHODY</button></a>';
}

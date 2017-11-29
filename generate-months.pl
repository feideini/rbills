open(months,">./months.csv");
$y=2000;
$c=1;
for ($i1=0;$i1<=59;++$i1)
{
for ($i2=1;$i2<=12;++$i2)
{
    $year=$y+$i1;
if ($i2<10)
{
    $month="0".$i2;
}
else
{
    $month="$i2";
}
print months "$c,$year-$month\n";
++$c;
}
}

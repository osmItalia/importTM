<?php
$xml=simplexml_load_file("fermate.osm");

$id=[];
foreach($xml->children() as $node){
//var_dump($node->attributes());
//var_dump($node->children());
$ref=$node->xpath('tag[@k="ref"]')[0]->attributes()['v']->__toString();

$c=str_split($ref);
do{
if($c[0]=='0') array_shift($c);
else break;
}
while($c[0]=='0');
$ref=implode("",$c);
$id[(int)$ref]=$node;
}

$r=ksort($id,SORT_NUMERIC);

$groups=array_chunk($id,5);

$k=0;
foreach($groups as $g)
{
$k++;
$file=simplexml_load_string("<osm version='0.6'></osm>");
foreach($g as $n){

//$file->addChild($n);
simplexml_import_xml($file,$n->asXml());
}

$k=str_pad($k,4,'0');
$file->asXml('results/fermateBari_'.$k.'.xml');
}

function simplexml_import_xml(SimpleXMLElement $parent, $xml, $before = false)
{
    $xml = (string)$xml;

    // check if there is something to add
    if ($nodata = !strlen($xml) or $parent[0] == NULL) {
        return $nodata;
    }

    // add the XML
    $node     = dom_import_simplexml($parent);
    $fragment = $node->ownerDocument->createDocumentFragment();
    $fragment->appendXML($xml);

    if ($before) {
        return (bool)$node->parentNode->insertBefore($fragment, $node);
    }

    return (bool)$node->appendChild($fragment);
}
?>
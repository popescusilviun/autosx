<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use Toolkit\Tools\UtilsTool;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Toolkit\SimpleHtmlDom;

/**
 * @author Silviu Popescu <popescu.silviun@gmail.com>
 */
class ImportCategoriesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('find:categories')
            ->setDescription('Import categories on website')
            ->setHelp(<<<EOT
The <info>%command.name%</info>Import categories
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        $categories = array(
            'Filtre' => array(
                'Filtru ulei' => 0,
                'Filtru aer' => array(
                    'Filtru aer' => 0,
                    'Sistem filtru aer sport' => 0
                ),
                'Filtru combustibil' => 0,
                'Filtru polen' => array(
                    'Filtru polen' => 0,
                    'Filtru polen carbon activ' => 0
                ),
                'Filtru cutie viteze automata' => 0,
                'Filtru servodirectie' => 0
            ),
            'Sistem franare' => array(
                'Placute frana disc' => array(
                    'Senzor uzura placute frana' => 0,
                    'Set accesorii placute frana' => 0,
                    'Set placute frana' => 0
                ),
                'Disc frana' => array(
                    'Disc frana' => 0,
                    'Protectie stropire disc frana' => 0,
                    'Surub disc frana' => 0
                ),
                'Saboti frana tambur' => array(
                    'Set accesorii saboti frana' => 0,
                    'Set saboti frana' => 0
                ),
                'Tambur frana' => array(
                    'Tambur frana' => 0,
                    'Accesorii frana tambur' => 0
                ),
                'Cablu frana mana' => 0,
                'Frana mana' => array(
                    'Set accesorii saboti frana mana' => 0,
                    'Set saboti frana mana' => 0
                ),
                'Etrier frana' => array(
                    'Etrier frana' => 0,
                    'Arc etrier frana' => 0,
                    'Piston etrier frana' => 0,
                    'Set busci ghidaj etrier frana' => 0,
                    'Set reparatie etrier frana' => 0,
                    'Suport etrier frana' => 0,
                    'Surub ghidare etrier frana' => 0
                ),
                'Cilindru receptor frana' => array(
                    'Cilindru receptor frana' => 0,
                    'Set reparatie cilindru receptor frana' => 0
                ),
                'Furtun frana' => 0,
                'Servofrana' => array(
                    'Servofrana' => 0,
                    'Senzor presiune' => 0,
                    'Supapa servofrana' => 0
                ),
                'Pompa centrala frana' => array(
                    'Pompa centrala frana' => 0,
                    'Set garnituri' => 0,
                    'Supapa presiune' => 0
                ),
                'Sistem ABS' => array(
                    'Senzor ABS' => 0,
                    'Releu ABS' => 0,
                    'Unitate control ABS' => 0,
                    'Unitate hidraulica ABS' => 0,
                    'Senzor turatie roata' => 0
                ),
                'Presiune frana' => array(
                    'Acumulator' => 0,
                    'Comutator' => 0,
                    'Regulator' => 0,
                    'Supapa sens presiune frana' => 0
                ),
                'Pompa vacuum' => array(
                    'Pompa vacuum frana' => 0
                ),
                'Contact / Senzor' => array(
                    'Contact frana' => 0
                )
            ),
            'Aprindere' => array(
                'Bujie' => 0,
                'Bobina inductie' => 0,
                'Senzor impulsuri / viteza / turatie' => array(
                    'Senzor impulsuri arbore cotit' => 0,
                    'Senzor impulsuri volanta' => 0,
                    'Senzor turatie cutie viteze' => 0,
                    'Senzor viteza turatie' => 0
                ),
                'Releu bujii incandescente' => 0
            ),
            'Distributie / Transmisie' => array(
                'Curea distributie' => array(
                    'Curea distributie' => 0,
                    'Garnitura capac distributie' => 0,
                    'Rola ghidare curea distributie' => 0,
                    'Rola intinzator curea distributie' => 0,
                    'Set curea distributie' => 0
                ),
                'Curea transmisie cu caneluri' => array(
                    'Curea transmisie cu caneluri' => 0,
                    'Rola ghidare curea transmisie cu caneluri' => 0,
                    'Rola intinzator curea transmisie cu caneluri' => 0,
                    'Set curea transmisie cu caneluri' => 0
                ),
                'Curea transmisie trapezoidala' => array(
                    'Curea transmisie trapezoidala' => 0,
                    'Rola ghidare' => 0,
                    'Intinzator curea transmisie' => 0
                ),
                'Distributie lant' => array(
                    'Chit lant distributie' => 0,
                    'Intinzator lant distributie' => 0,
                    'Lant distributie' => 0,
                    'Patina ghidare lant distributie' => 0,
                    'Pinion lant distributie' => 0,
                    'Lant arbore intermediar' => 0
                ),
                'Fulie curea' => array(
                    'Fulie curea arbore cotit' => 0,
                    'Fulie alternator' => 0,
                    'Fulie pompa apa' => 0,
                    'Fulie pompa servodirectie' => 0,
                    'Surub fulie arbore cotit' => 0
                )
            ),
            'Directie' => array(
                'Articulatii directie' => array(
                    'Bieleta directie' => 0,
                    'Cap bara' => 0,
                    'Articulatie amortizor directie' => 0,
                    'Articulatie coloana directie' => 0,
                    'Set montaj cap bara' => 0
                ),
                'Bara directie' => array(
                    'Bara directie' => 0,
                    'Brat bara directie' => 0,
                    'Bucsa bara directie' => 0,
                    'Surub aliniament roti' => 0,
                    'Set reparatie pivot' => 0,
                    'Teava bara directie' => 0
                ),
                'Burduf bieleta directie' => 0,
                'Caseta directie' => array(
                    'Caseta directie' => 0,
                    'Set reparatie caseta directie' => 0,
                    'Suport caseta directie' => 0,
                    'Garnitura caseta directie' => 0,
                    'Inel etansare caseta directie' => 0
                ),
                'Pompa servodirectie' => array(
                    'Pompa servodirectie' => 0,
                    'Furtun servodirectie' => 0,
                    'Garnitura arbore pompa servodirectie' => 0,
                    'Set garnituri pompa servodirectie' => 0
                ),
                'Rezervor ulei servodirectie' => array(
                    'Rezervor ulei sevodirectie' => 0,
                    'Capac rezervor ulei servodirectie' => 0,
                    'Sorb ulei hidraulic' => 0
                )
            ),
            'Suspensie roata' => array(
                'Brat suspensie roata' => array(
                    'Brat suspensie roata' => 0,
                    'Surub corectare inclinare' => 0,
                    'Surub brat' => 0,
                    'Set suspensie roata' => 0,
                    'Set reparatie suspensie roata' => 0
                ),
                'Bucsa brat suspensie roata' => array(
                    'Bucsa brat' => 0,
                    'Camasa bucsa brat' => 0,
                    'Set reparatie bucsa brat' => 0
                ),
                'Pivot brat suspensie roata' => array(
                    'Pivot brat' => 0,
                    'Surub pivot' => 0,
                    'Placuta asigurare pivot' => 0,
                    'Set reparatie pivot' => 0
                ),
                'Bieleta antiruliu' => array(
                    'Bieleta antiruliu' => 0,
                    'Bucsa bieleta antiruliu' => 0,
                    'Set reparatie bieleta antiruliu' => 0
                ),
                'Bara stabilizatoare' => array(
                    'Bara stabilizatoare' => 0,
                    'Bucsa bara stabilizatoare' => 0,
                    'Set reparatie bara stabilizatoare' => 0
                ),
                'Bucsa punte' => array(
                    'Bucsa punte' => 0,
                    'Rulment punte' => 0,
                    'Surub bucsa punte' => 0,
                    'Set reparatie bucsa punte' => 0
                ),
                'Montaj roata' => array(
                    'Distantier marire ecartament' => 0,
                    'Surub roata' => 0,
                    'Piulita roata' => 0,
                    'Capac butuc roata' => 0,
                    'Capac piulita roata' => 0
                ),
                'Sistem control presiune pneuri' => array(
                    'Senzor presiune pneuri' => 0,
                    'Supapa presiune pneuri' => 0,
                    'Unitate control presiune pneuri' => 0
                )
            ),
            'Suspensie arc' => array(
                'Amortizor' => array(
                    'Amortizor' => 0,
                    'Bucsa amortizor' => 0,
                    'Piulita amortizor' => 0,
                    'Set montare amortizor' => 0,
                    'Set suspensie amortizor' => 0
                ),
                'Rulment sarcina' => array(
                    'Flansa rulment sarcina amortizor' => 0,
                    'Rulment sarcina amortizor' => 0,
                    'Inel rulment' => 0,
                    'Garnitura rulment' => 0
                ),
                'Burduf protectie' => array(
                    'Burduf protectie amortizor' => 0,
                    'Set protectie amortizor' => 0,
                    'Tampon cauciuc amortizor' => 0
                ),
                'Arc spiral' => array(
                    'Arc spiral' => 0,
                    'Bucsa arc' => 0,
                    'Flansa arc' => 0,
                    'Saiba arc' => 0,
                    'Set suspensie arcuri' => 0
                ),
                'Suspensie pneumatica' => array(
                    'Compresor aer' => 0,
                    'Burduf suspensie pneumatica' => 0,
                    'Conducta presiune' => 0,
                    'Piese compresor aer' => 0
                )
            ),
            'Angrenare roata' => array(
                'Rulment roata' => array(
                    'Rulment roata' => 0,
                    'Capac rulment roata' => 0,
                    'Set montaj rulment roata' => 0
                ),
                'Butuc roata' => array(
                    'Butuc roata' => 0,
                    'Capac butuc roata' => 0,
                    'Simering butuc roata' => 0,
                    'Set reparatie butuc roata' => 0
                ),
                'Cap planetara' => array(
                    'Cap planetara roata' => 0,
                    'Cap planetara cutie viteze' => 0
                ),
                'Burduf cap planetara' => 0,
                'Planetara' => array(
                    'Planetara' => 0,
                    'Piulita planetara' => 0
                )
            ),
            'Antrenare punte' => array(
                'Ax cardanic' => array(
                    'Flansa cardan' => 0,
                    'Rulment cardan' => 0,
                    'Capac rulment cardan' => 0,
                    'Simering cardan' => 0,
                    'Suport cardan' => 0,
                    'Stift cardan' => 0
                ),
                'Diferential' => array(
                    'Cruce diferential' => 0,
                    'Flansa diferential' => 0,
                    'Roata dintata diferential' => 0,
                    'Simering diferential' => 0,
                    'Placa presiune diferential' => 0,
                    'Set garnituri diferential' => 0
                ),
                'Cutie transfer' => array(
                    'Simering cutie transfer' => 0,
                    'Suport cutie transfer' => 0,
                    'Set garnituri cutie transfer' => 0
                )
            ),
            'Ambreiaj' => array(
                'Set ambreiaj' => array(
                    'Set ambreiaj' => 0,
                    'Set suruburi ambreiaj' => 0
                ),
                'Placa presiune ambreiaj' => array(
                    'Placa presiune ambreiaj' => 0,
                    'Surub placa presiune' => 0,
                    'Colier placa presiune' => 0,
                    'Taler placa presiune ambreiaj' => 0
                ),
                'Disc ambreiaj' => 0,
                'Rulment presiune ambreiaj' => array(
                    'Rulment presiune ambreiaj' => 0,
                    'Simering rulment presiune' => 0,
                    'Capac rulment presiune ambreiaj' => 0
                ),
                'Cilindru receptor ambreiaj' => array(
                    'Cilindru receptor ambreiaj' => 0,
                    'Tija cilindru receptor' => 0,
                    'Set reparatie cilindru receptor ambreiaj' => 0
                ),
                'Pompa ambreiaj' => array(
                    'Pompa ambreiaj' => 0,
                    'Tija pompa ambreiaj' => 0,
                    'Supapa reglare pompa ambreiaj' => 0,
                    'Set reparatie pompa ambreiaj' => 0
                ),
                'Cablu ambreiaj' => array(
                    'Cablu ambreiaj' => 0,
                    'Sector dintat ambreiaj' => 0
                ),
                'Volanta' => array(
                    'Volanta' => 0,
                    'Set surub volanta' => 0,
                    'Coroana dintata volanta' => 0
                ),
                'Comanda ambreiaj' => array(
                    'Aparatoare pedala ambreiaj' => 0,
                    'Conducta ambreiaj' => 0,
                    'Pedala ambreiaj' => 0,
                    'Rulment levier ambreiaj' => 0,
                    'Supapa sens actionare ambreiaj' => 0
                )
            ),
            'Esapament' => array(
                'Toba esapament' => array(
                    'Toba esapament finala' => 0,
                    'Toba esapament mijlocie' => 0,
                    'Toba esapament sport' => 0
                ),
                'Teava esapament' => array(
                    'Teava evacuare esapament' => 0,
                    'Garnitura teava evacuare' => 0,
                    'Racord flexibil esapament' => 0
                ),
                'Galerie evacuare esapament' => array(
                    'Galerie evacuare esapament' => 0,
                    'Surub galerie evacuare' => 0,
                    'Garnitura galerie evacuare' => 0
                ),
                'Catalizator' => array(
                    'Catalizator' => 0,
                    'Precatalizator' => 0,
                    'Set montaj catalizator' => 0,
                    'Set montaj precatalizator' => 0
                ),
                'Elemente montaj esapament' => array(
                    'Colier toba esapament' => 0,
                    'Suport toba esapament' => 0,
                    'Surub toba esapament' => 0
                )
            ),
            'Evacuare gaze' => array(
                'Radiator intercooler' => array(
                    'Radiator intercooler' => 0,
                    'Releu ventilator racire intercooler' => 0
                ),
                'Sonda lambda' => array(
                    'Sonda lambda' => 0,
                    'Adaptor sonda lambda' => 0,
                    'Surub suport sonda lambda' => 0
                ),
                'Turbosuflanta' => array(
                    'Turbosuflanta' => 0,
                    'Convertor presiune turbosuflanta' => 0,
                    'Set montaj turbosuflanta' => 0
                ),
                'Supapa AGR' => array(
                    'Garnitura ventil AGR' => 0,
                    'Garnitura conducta AGR' => 0,
                    'Conducta AGR' => 0
                ),
                'Recirculare gaze evacuare - EGR' => array(
                    'Supapa EGR' => 0,
                    'Contactor temperatura EGR' => 0,
                    'Radiator EGR' => 0
                ),
                'Presiune gaze evacuare' => array(
                    'Convertor presiune gaze evacuare' => 0,
                    'Senzor presiune gaze evacuare' => 0
                )
            ),
            'Admisie aer' => array(
                'Debitmetru aer' => array(
                    'Senzor debit aer' => 0
                ),
                'Clapeta control' => array(
                    'Carcasa clapeta' => 0,
                    'Senzor pozitie clapeta acceleratie' => 0,
                    'Ventil comutare clapeta' => 0,
                    'Element reglaj clapeta' => 0
                ),
                'Galerie admisie' => array(
                    'Garnitura galerie admisie' => 0,
                    'Senzor temperatura aer admisie' => 0,
                    'Senzor presiune galerie admisie' => 0,
                    'Galerie admisie' => 0,
                    'Flansa incalzire' => 0
                )
            ),
            'Piese motor' => array(
                'Suport motor' => array(
                    'Suport motor' => 0,
                    'Ventil comutare suport motor' => 0
                ),
                'Capac supape' => array(
                    'Buson umplere ulei' => 0,
                    'Garnitura capac supape' => 0,
                    'Surub capac supape' => 0
                ),
                'Ax cu came' => array(
                    'Ax cu came' => 0,
                    'Pinion' => 0,
                    'Senzor pozitie' => 0,
                    'Simering' => 0,
                    'Supapa comanda pozitie' => 0,
                    'Regulator' => 0
                ),
                'Supapa motor' => array(
                    'Supapa admisie' => 0,
                    'Supapa evacuare' => 0,
                    'Ghid supapa' => 0,
                    'Simering supapa' => 0,
                    'Scaun supapa' => 0
                ),
                'Culbutor / Tachet supapa' => array(
                    'Culbutor tren supape' => 0,
                    'Tachet supapa' => 0,
                    'Pastila tachet supapa' => 0,
                    'Set ventile' => 0
                ),
                'Chiuloasa' => array(
                    'Garnitura chiuloasa' => 0,
                    'Chiuloasa' => 0,
                    'Surub chiuloasa' => 0,
                    'Senzor temperatura chiuloasa' => 0,
                    'Senzor batai' => 0,
                    'Furtun epurator gaze' => 0
                ),
                'Arbore cotit' => array(
                    'Segmenti' => 0,
                    'Piston' => 0,
                    'Cuzineti biela' => 0,
                    'Cuzineti palier' => 0,
                    'Simering arbore cotit' => 0,
                    'Pinion arbore' => 0
                ),
                'Set garnituri motor' => array(
                    'Set garnituri complet motor' => 0,
                    'Set garnituri capac supape' => 0,
                    'Set garnituri chiuloasa' => 0,
                    'Set garnituri baie ulei' => 0
                )
            ),
            'Ungere motor' => array(
                'Baie ulei' => array(
                    'Baie ulei' => 0,
                    'Garnitura baie ulei' => 0,
                    'Surub golire baie ulei' => 0,
                    'Garnitura surub golire baie ulei' => 0
                ),
                'Pompa ulei' => array(
                    'Pompa ulei' => 0,
                    'Garnitura pompa ulei' => 0,
                    'Lant pompa ulei' => 0,
                    'Intinzator lant' => 0,
                    'Pinion pompa ulei' => 0,
                    'Simering pompa ulei' => 0
                ),
                'Senzor ulei' => array(
                    'Senzor nivel ulei' => 0,
                    'Senzor temperatura ulei' => 0,
                    'Senzor presiune ulei' => 0,
                    'Joja ulei' => 0
                )
            ),
            'Racire motor' => array(
                'Pompa apa' => array(
                    'Pompa apa' => 0,
                    'Garnitura pompa apa' => 0
                ),
                'Termostat' => array(
                    'Termostat' => 0,
                    'Carcasa termostat' => 0,
                    'Garnitura termostat' => 0,
                    'Garnitura carcasa termostat' => 0
                ),
                'Radiator racire apa' => array(
                    'Radiator racire apa' => 0,
                    'Buson radiator' => 0,
                    'Suport radiator' => 0,
                    'Vas expansiune' => 0,
                    'Buson vas expansiune' => 0
                ),
                'Flansa apa / Furtun apa' => array(
                    'Flansa apa' => 0,
                    'Garnitura flansa apa' => 0,
                    'Dop flansa apa' => 0,
                    'Furtun radiator racire apa' => 0
                ),
                'Comutator / Senzor' => array(
                    'Senzor nivel lichid racire' => 0,
                    'Senzor temperatura lichid racire' => 0,
                    'Senzor temperatura motor' => 0,
                    'Comutator control nivel lichid racire' => 0
                ),
                'Ventilator radiator apa' => array(
                    'Ventilator radiator' => 0,
                    'Suport ventilator' => 0,
                    'Termocupla ventilator' => 0,
                    'Vascocuplaj ventilator' => 0,
                    'Rezistor ventilator' => 0,
                    'Releu ventilator' => 0
                )
            ),
            'Injectie / Carburatie' => array(
                'Sistem injectie' => array(
                    'Opritor injectie' => 0,
                    'Conducta inalta presiune' => 0,
                    'Scut protectie termica' => 0,
                    'Supapa control presiune' => 0,
                    'Unit comanda injectie' => 0,
                    'Ventil instalatie injectie' => 0
                ),
                'Pompa injectie' => array(
                    'Pompa injectie' => 0,
                    'Garnitura pompa injectie' => 0,
                    'Roata dintata pompa injectie' => 0,
                    'Simering pompa injectie' => 0,
                    'Pompa inalta presiune' => 0
                ),
                'Diuza injector' => array(
                    'Diuza injector' => 0,
                    'Garnitura diuza injector' => 0
                )
            ),
            'Alimentare combustibil' => array(
                'Rezervor combustibil' => array(
                    'Rezervor combustibil' => 0,
                    'Buson rezervor combustibil' => 0
                ),
                'Pompa combustibil' => array(
                    'Pompa combustibil' => 0,
                    'Releu pompa combustibil' => 0
                ),
                'Furtune / Conducte' => 0,
                'Instalatie alimentare' => 0,
                'Supape' => 0
            ),
            'Cutie viteze' => array(
                'Cutie viteze manuala' => array(
                    'Cutie viteze manuala' => 0,
                    'Garnitura carcasa cutie viteza transmisie' => 0,
                    'Suport transmisie manuala' => 0,
                ),
                'Cutie viteze automata' => array(
                    'Suport transmisie automata' => 0,
                    'Ulei cutie automata' => 0
                )
            ),
            'Aer conditionat' => array(
                'Compresor climatizare' => 0,
                'Radiator climatizare' => array(
                    'Radiator climatizare' => 0,
                    'Condensator climatizare' => 0
                ),
                'Vaporizator' => array(
                    'Evaporator aer conditionat' => 0
                ),
                'Uscator aer conditionat' => 0,
                'Supape' => 0,
                'Intrerupator' => array(
                    'Comutator inalta presiune climatizare' => 0,
                    'Comutator presiune aer conditionat' => 0,
                ),
                'Control aer conditionat' => array(
                    'Element control aer conditionat' => 0,
                    'Element reglare clapeta carburator' => 0
                )
            ),
            'Incalzire' => array(
                'Schimbator caldura incalzire habitaclu' => 0,
                'Motor ventilator' => array(
                    'Ventilator habitaclu' => 0
                ),
                'Unitate control' => array(
                    'Rezistor ventilator habitaclu' => 0
                ),
                'Element control aer conditionat' => 0
            ),
            'Electrice' => array(
                'Becuri' => array(
                    'Bec far' => array(
                        'Bec far faza lunga' => 0,
                        'Bec far principal' => 0,
                        'Bec incandescent bec lumina zi' => 0
                    ),
                    'Bec semnal' => 0,
                    'Bec proiector' => 0,
                    'Bec stop' => 0,
                    'Bec lampa frana' => 0,
                    'Bec lampa ceata' => 0,
                    'Bec pozitie' => 0,
                    'Bec iluminare habitaclu' => 0,
                    'Bec iluminare numar circulatie' => 0,
                    'Bec iluminare portbagaj' => 0,
                    'Bec lampa marsalier' => 0,
                    'Bec lumina citire' => 0,
                    'Bec lumina portiera' => 0,
                    'Bec lumini stationare' => 0,
                    'Bec far pentru viraje' => 0
                ),
                'Electromotor' => array(
                    'Electromotor' => 0,
                    'Comutator pornire' => 0
                ),
                'Alternator' => array(
                    'Alternator' => 0,
                    'Punte diode' => 0,
                    'Regulator alternator' => 0
                ),
                'Intrerupatoare / Relee / Control' => array(
                    'Bloc lumini' => 0,
                    'Comutator lampa marsalier' => 0
                ),
                'Sistem semnalizare / Releu' => 0,
                'Claxon' => 0,
                'Senzor' => array(
                    'Senzor lumini xenon' => 0,
                    'Senzor nivel lichid racire' => 0,
                    'Senzor presiune aer' => 0,
                    'Senzor presiune combustibil' => 0,
                    'Senzor batai' => 0,
                    'Senzor temperatura exterioara' => 0,
                    'Senzor viteza' => 0
                ),
                'Relee' => array(
                    'Releu pompa combustibil' => 0
                ),
                'Indicatoare' => array(
                    'Senzor temperatura exterioara' => 0,
                    'Senzor viteza' => 0
                ),
                'Unitate control' => array(
                    'Rezistor ventilator habitaclu' => 0
                ),
                'Set cabluri' => array(
                    'Set reparatie set cabluri' => 0
                )
            ),
            'Sistem inchidere' => array(
                'Incuietoare exterior' => 0,
                'Butuc incuietoare' => array(
                    'Cilindru inchidere aprindere' => 0
                ),
                'Manere' => 0,
                'Componente' => 0
            ),
            'Sistem curatare' => array(
                'Stergator parbriz' => array(
                    'Lamela stergator' => 0
                ),
                'Motoras stergator parbriz' => 0,
                'Pompa spalator parbriz' => array(
                    'Pompa apa spalare parbriz' => 0
                ),
                'Brat stergator' => 0,
                'Parghie stergator parbriz' => array(
                    'Brat ghidaj stergator parbriz' => 0
                ),
                'Comutator / Releu' => array(
                    'Comutator stergator' => 0
                ),
                'Spalare faruri' => array(
                    'Pompa apa spalare faruri' => 0
                )
            ),
            'Echipament interior' => array(
                'Amortizor capota / portbagaj' => array(
                    'Amortizor capota' => 0,
                    'Amortizor portbagaj' => 0
                ),
                'Tempomat' => 0,
                'Actionare geam' => array(
                    'Macara geam' => 0
                ),
                'Mecanism parghii manual' => array(
                    'Bloc lumini' => 0,
                    'Pedala acceleratie' => 0,
                ),
                'Accesorii' => 0,
                'Bricheta electrica' => 0
            )
        );

        $a = 1;
        foreach($categories as $category_1 => $cats) {
            $output->writeln(sprintf('Categorie parinte <info>%s</info>', $category_1));
            $cat_1 = $em->getRepository('AppBundle:Category')->findOneBy(array('nume' => $category_1));

            if(!$cat_1) {
                $cat_1 = new Category();
                $cat_1->setNume($category_1);
                $cat_1->setSlug(UtilsTool::makeSlug($category_1));
                $cat_1->setLevel(0);
                $cat_1->setPosition($a);
                $em->persist($cat_1);
                $em->flush();
            }
            $b = 1;
            foreach($cats as $category_2 => $catz) {
                $output->writeln(sprintf('Categorie parinte <info>%s</info> => <info>%s</info>', $category_1, $category_2));
                $cat_2 = $em->getRepository('AppBundle:Category')->findOneBy(array('nume' => $category_2, 'parent' => $cat_1));

                if(!$cat_2) {
                    $cat_2 = new Category();
                    $cat_2->setNume($category_2);
                    $cat_2->setSlug(UtilsTool::makeSlug($category_2));
                    $cat_2->setLevel(1);
                    $cat_2->setPosition($b);
                    $cat_2->setParent($cat_1);
                    $em->persist($cat_2);
                    $em->flush();
                }
                if (is_array($catz)) {
                    $c = 1;
                    foreach ($catz as $category_3 => $cat) {
                        if (!is_array($cat)) {
                            $output->writeln(sprintf('Categorie parinte <info>%s</info> => <info>%s</info> => <info>%s</info>', $category_1, $category_2, $category_3));
                            $cat_3 = $em->getRepository('AppBundle:Category')->findOneBy(array('nume' => $category_3, 'parent' => $cat_2));

                            if(!$cat_3) {
                                $cat_3 = new Category();
                                $cat_3->setNume($category_3);
                                $cat_3->setSlug(UtilsTool::makeSlug($category_3));
                                $cat_3->setLevel(2);
                                $cat_3->setPosition($c);
                                $cat_3->setParent($cat_2);
                                $em->persist($cat_3);
                                $em->flush();
                                $c++;
                            }
                        }

                    }
                }
                $b++;
            }
            $a++;
        }

    }
}
<?php
class CITRestaurantsImportHandler extends SQLIImportAbstractHandler implements ISQLIImportHandler
{
    protected $rowIndex = 0;

    protected $rowCount;

    protected $currentGUID;

    public function __construct( SQLIImportHandlerOptions $options = null )
    {
        parent::__construct( $options );
        $this->remoteIDPrefix = $this->getHandlerIdentifier().'-';
        $this->currentRemoteIDPrefix = $this->remoteIDPrefix;
        $this->options = $options;
    }

    public function initialize()
    {
		$this->tree = array();
        $csvFile = $this->handlerConfArray['CsvFile'];
        $options = new SQLICSVOptions( array(
			'csv_path' => $csvFile,
            'delimiter' => ';',
			'enclosure'   => '"'
		) );
        $csvDoc = new SQLICSVDoc( $options );        
		$csvDoc->parse();        
        $this->dataSource = $csvDoc->rows;   
    }

    public function getProcessLength()
    {
        if( !isset( $this->rowCount ) )
        {
            $this->rowCount = count( $this->dataSource );
            $this->maxRowCount = $this->rowCount;
        }		
        return $this->rowCount;
    }

    public function getNextRow()
    {
        if( $this->rowIndex < $this->rowCount )
        {
            $row = $this->dataSource[$this->rowIndex];
            $this->rowIndex++;
        }
        else
        {
            $row = false; // We must return false if we already processed all rows
        }
        return $row;
    }

    public function process( $row )
    {                
        
        //id
        //categoryName
        //mainLocale
        //classificationsIt
        //classificationsDe
        //classificationsEn
        //name
        //addressIt
        //addressDe
        //addressEn
        //phone
        //urlPage
        //email
        //timetableIt
        //timetableDe
        //timetableEn
        //closingDaysIt
        //closingDaysDe
        //closingDaysEn
        //pricesIt
        //pricesDe
        //pricesEn
        //equipmentIt
        //equipmentDe
        //equipmentEn
        //pictureUrl
        //shortDescriptionIt
        //shortDescriptionDe
        //shortDescriptionEn
        //pictureWidth
        //pictureHeight
        //latitude
        //longitude
        //lastModificationDate
        //status
        
        $this->currentGUID = $remote_id = $row->id;

        $contentOptions = new SQLIContentOptions( array(
            'class_identifier'      => 'ristorante',
            'remote_id'				=> $remote_id,
            'language'              => 'ita-IT'
        ) );
        $content = SQLIContent::create( $contentOptions );
		$content->fields->titolo = (string) $row->name;
        $content->fields->indirizzo = (string) $row->addressIt;
        $content->fields->telefono = (string) $row->phone;
        $content->fields->url = (string) $row->urlPage;
        $content->fields->email = (string) $row->email;
        $content->fields->orario = (string) $row->timetableIt;
        $content->fields->riposo = (string) $row->closingDaysIt;
        $content->fields->prezzo_medio_in_euro = (string) $row->pricesIt;
        $content->fields->servizi_offerti = $this->serviziOfferti( $row->equipmentIt, $row->equipmentEn, $row->equipmentDe );
        $content->fields->image = self::getImage( (string) $row->pictureUrl );
        $content->fields->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionIt );     
        
        $content->addTranslation( 'eng-GB' );        
		$content->fields['eng-GB']->titolo = (string) $row->name;
        $content->fields['eng-GB']->indirizzo = (string) $row->addressEn;
        $content->fields['eng-GB']->telefono = (string) $row->phone;
        $content->fields['eng-GB']->url = (string) $row->urlPage;
        $content->fields['eng-GB']->email = (string) $row->email;
        $content->fields['eng-GB']->orario = (string) $row->timetableEn;
        $content->fields['eng-GB']->riposo = (string) $row->closingDaysEn;
        $content->fields['eng-GB']->prezzo_medio_in_euro = (string) $row->pricesEn;
        $content->fields['eng-GB']->servizi_offerti = $this->serviziOfferti( $row->equipmentIt, $row->equipmentEn, $row->equipmentDe );
        $content->fields->image = self::getImage( (string) $row->pictureUrl );
        $content->fields->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionEn );
				

        $parentNodeId = $this->handlerConfArray['DefaultParentNodeID'];
        $content->addLocation( SQLILocation::fromNodeID( $parentNodeId ) );
        $publisher = SQLIContentPublisher::getInstance();
        $publisher->publish( $content );
        unset( $content ); 
    }
    
    public static function getImage( $string )
    {
        return '/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/images/' . $string;
    }
    
    protected function serviziOfferti( $it, $en, $de )
    {
        $ids = array();
        $valuesIt = explode( ';', $it );
        $valuesEn = explode( ';', $en );
        //$valuesDe = explode( ';', $de );
        foreach( $valuesIt as $index => $value )
        {
            $value = trim( $value );
            $contentOptions = new SQLIContentOptions( array(
                'class_identifier'      => 'tipo_servizio_ristoranti',
                'remote_id'				=> 'cit_' . md5( $value ),
                'language'              => 'ita-IT'
            ) );
            $content = SQLIContent::create( $contentOptions );
            $content->fields->titolo = $value;
            if ( isset( $valuesEn[$index] ) )
            {
                $content->addTranslation( 'eng-GB' );
                $content->fields['eng-GB']->titolo = trim( $valuesEn[$index] );
            }
            
            //if ( isset( $valuesDe[$index] ) )
            //{
            //    $content->addTranslation( 'eng-GB' );
            //    $content->fields['eng-GB']->titolo = trim( $valuesDe[$index] );
            //}

            $content->addLocation( SQLILocation::fromNodeID( 591717 ) );
            $publisher = SQLIContentPublisher::getInstance();
            $publisher->publish( $content );
            $ids[] = $content->id;
            unset( $content );
        }
        return implode( '-', $ids );
    }

    public function cleanup()
    {
        return;
    }

    public function getHandlerName()
    {
        return 'Comune In Tasca Import Handler';
    }

    public function getHandlerIdentifier()
    {
        return 'citimporthandler';
    }

    public function getProgressionNotes()
    {
        return '';
    }
}

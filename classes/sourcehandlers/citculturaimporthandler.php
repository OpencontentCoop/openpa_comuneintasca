<?php
class CITCulturaImportHandler extends SQLIImportAbstractHandler implements ISQLIImportHandler
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
        //mainLocale
        //categoryName
        //classificationsIt
        //classificationsDe
        //classificationsEn
        //nameIt
        //nameDe
        //nameEn
        //address
        //urlPage
        //email
        //phone
        //relatedContentId
        //pictureUrl
        //shortDescriptionIt
        //shortDescriptionDe
        //shortDescriptionEn
        //htmlDescriptionIt
        //htmlDescriptionDe
        //htmlDescriptionEn
        //pictureWidth
        //latitude
        //longitude
        //contactFullName
        //lastModificationDate
        //status
        
        $this->currentGUID = $remote_id = $row->id;
        
        if ( trim( $row->classificationsIt ) != "Musei" )
        {
            $tipoLuogo = null;
            if ( $row->classificationsIt == 'Chiese' )
            {
                $tipoLuogo = '775485';    
            }
            elseif ( $row->classificationsIt == 'Edifici storici' )
            {
                $tipoLuogo = '775486';    
            }
            elseif ( $row->classificationsIt == 'Aree Archeologiche' )
            {
                $tipoLuogo = '775487';    
            }
            elseif ( $row->classificationsIt == 'Altri siti di interesse storico artistico' )
            {
                $tipoLuogo = '775488';    
            }
            elseif ( $row->classificationsIt == 'Natura' )
            {
                $tipoLuogo = '775489';    
            }
            
            // Luoghi
            $contentOptions = new SQLIContentOptions( array(
                'class_identifier'      => 'luogo',
                'remote_id'				=> $remote_id,
                'language'              => 'ita-IT'
            ) );
            $content = SQLIContent::create( $contentOptions );
            $content->fields->title = (string) $row->nameIt;		
            $content->fields->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionIt );
            $content->fields->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionIt );
            $content->fields->image = self::getImage( (string) $row->pictureUrl );
            $content->fields->indirizzo = (string) $row->address;
            $content->fields->geo = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->address;
            $content->fields->tipo_luogo = $tipoLuogo;
            $content->fields->url = (string) $row->urlPage;
            $content->fields->email = (string) $row->email;
            $content->fields->telefono = (string) $row->phone;
            
            $content->addTranslation( 'eng-GB' );            
            $content->fields['eng-GB']->title = (string) $row->nameEn;		
            $content->fields['eng-GB']->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionEn );
            $content->fields['eng-GB']->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionEn );
            $content->fields['eng-GB']->image = self::getImage( (string) $row->pictureUrl );
            $content->fields['eng-GB']->indirizzo = (string) $row->address;
            $content->fields['eng-GB']->geo = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->address;
            $content->fields['eng-GB']->tipo_luogo = $tipoLuogo;
            $content->fields['eng-GB']->url = (string) $row->urlPage;
            $content->fields['eng-GB']->email = (string) $row->email;
            $content->fields['eng-GB']->telefono = (string) $row->phone;
        }
        else
        {
            $tipoServizio = null;
            if ( $row->classificationsIt == 'Musei' )
            {
                $tipoServizio = '622571';    
            }
            
            $contentOptions = new SQLIContentOptions( array(
                'class_identifier'      => 'servizio_sul_territorio',
                'remote_id'				=> $remote_id,
                'language'              => 'ita-IT'
            ) );
            
            $content = SQLIContent::create( $contentOptions );
            $content->fields->titolo = (string) $row->nameIt;		
            $content->fields->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionIt );
            $content->fields->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionIt );
            $content->fields->image = self::getImage( (string) $row->pictureUrl );
            $content->fields->indirizzo = (string) $row->address;
            $content->fields->gps = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->address;            
            $content->fields->tipologia_servizio = $tipoServizio;
            $content->fields->url = (string) $row->urlPage;
            $content->fields->email = (string) $row->email;
            $content->fields->telefono = (string) $row->phone;
            
            $content->addTranslation( 'eng-GB' );            
            $content->fields['eng-GB']->titolo = (string) $row->nameEn;		
            $content->fields['eng-GB']->abstract = SQLIContentUtils::getRichContent( (string) $row->shortDescriptionEn );
            $content->fields['eng-GB']->descrizione = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionEn );
            $content->fields['eng-GB']->image = self::getImage( (string) $row->pictureUrl );
            $content->fields['eng-GB']->indirizzo = (string) $row->address;
            $content->fields['eng-GB']->gps = '1|#' . $row->latitude . '|#' . $row->longitude . '|#' . $row->address;
            $content->fields->tipologia_servizio = $tipoServizio;
            $content->fields['eng-GB']->url = (string) $row->urlPage;
            $content->fields['eng-GB']->email = (string) $row->email;
            $content->fields['eng-GB']->telefono = (string) $row->phone;
            
        }

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

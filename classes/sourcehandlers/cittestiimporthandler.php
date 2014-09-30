<?php
class CITTestiImportHandler extends SQLIImportAbstractHandler implements ISQLIImportHandler
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
        //classifications
        //nameIt
        //nameDe
        //nameEn
        //address
        //urlPage
        //email
        //phone
        //status
        //pictureUrl
        //shortDescriptionIt
        //shortDescriptionDe
        //shortDescriptionEn
        //lastModificationDate
        //htmlDescriptionIt
        //htmlDescriptionDe
        //htmlDescriptionEn
        
        $this->currentGUID = $remote_id = $row->id;

        $contentOptions = new SQLIContentOptions( array(
            'class_identifier'      => 'item_comuneintasca',
            'remote_id'				=> $remote_id,
            'language'              => 'ita-IT'
        ) );
        $content = SQLIContent::create( $contentOptions );
		$content->fields->title = (string) $row->nameIt;		
		$content->fields->tooltip = (string) $row->address;				
		$content->fields->image = self::getImage( (string) $row->pictureUrl );
		$content->fields->abstract = strip_tags( (string) $row->shortDescriptionIt );		
		$content->fields->description = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionIt );			
		$content->fields->classifications = trim( $row->classifications );			
		$content->fields->address = trim( $row->address );
        $content->fields->info = SQLIContentUtils::getRichContent( (string) $row->infoIt );

        
        $content->addTranslation( 'eng-GB' );
		$content->fields['eng-GB']->title = (string) $row->nameEn;		
		$content->fields['eng-GB']->tooltip = (string) $row->address;				
		$content->fields['eng-GB']->image = self::getImage( (string) $row->pictureUrl );
		$content->fields['eng-GB']->abstract = strip_tags( (string) $row->shortDescriptionEn );		
		$content->fields['eng-GB']->description = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionEn );
        $content->fields['eng-GB']->classifications = trim( $row->classifications );			
		$content->fields['eng-GB']->address = trim( $row->address );
        $content->fields['eng-GB']->info = SQLIContentUtils::getRichContent( (string) $row->infoEn );
        
        $content->addTranslation( 'ger-DE' );
        $content->fields['ger-DE']->title = (string) $row->nameDe;		
        $content->fields['ger-DE']->tooltip = (string) $row->address;				
        $content->fields['ger-DE']->image = self::getImage( (string) $row->pictureUrl );
        $content->fields['ger-DE']->abstract = strip_tags( (string) $row->shortDescriptionDe );		
        $content->fields['ger-DE']->description = SQLIContentUtils::getRichContent( (string) $row->htmlDescriptionDe );
        $content->fields['ger-DE']->classifications = trim( $row->classifications );			
        $content->fields['ger-DE']->address = trim( $row->address );
        $content->fields['ger-DE']->info = SQLIContentUtils::getRichContent( (string) $row->infoDe );

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

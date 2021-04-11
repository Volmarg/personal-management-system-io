<?php


namespace App\DTO;


class ParentChildDTO {

    CONST KEY_TYPE     = 'type';
    CONST KEY_NAME     = 'name';
    CONST KEY_ID       = 'id';
    CONST KEY_DEPTH    = 'depth';
    CONST KEY_CHILDREN = 'children';

    /**
     * @var string $type
     */
    private string $type = "";

    /**
     * @var string $name
     */
    private string $name = "";

    /**
     * @var string $id
     */
    private string $id = "";

    /**
     * Info: can be used to build tree of hierarchy
     * @var int $depth
     */
    private int $depth = 0;

    /**
     * @var ParentChildDTO[] $children
     */
    private array $children = [];

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getChildren(): array {
        return $this->children;
    }

    /**
     * @param array $children
     */
    public function setChildren(array $children): void {
        $this->children = $children;
    }

    /**
     * @param mixed $child
     */
    public function addChild($child){
        $this->children[] = $child;
    }

    /**
     * @return int
     */
    public function getDepth(): int {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth): void {
        $this->depth = $depth;
    }

    /**
     * Return array representation of self
     *
     * @return array
     */
    public function toArray(): array
    {
        $dataArray[self::KEY_ID]    = $this->getId();
        $dataArray[self::KEY_NAME]  = $this->getName();
        $dataArray[self::KEY_DEPTH] = $this->getDepth();
        $dataArray[self::KEY_TYPE]  = $this->getType();

        foreach($this->getChildren() as $child){
            if( !($child instanceof ParentChildDTO) ){ // can be plain array already
                $dataArray[self::KEY_CHILDREN][] = $child;
                continue;
            }

            $childDataArray = $child->toArray();
            if( !array_key_exists(self::KEY_CHILDREN, $dataArray) ){
                $dataArray[self::KEY_CHILDREN] = $dataArray;
            }else{
                $dataArray[self::KEY_CHILDREN] = array_merge($dataArray[self::KEY_CHILDREN], $childDataArray);
            }
        }

        return $dataArray;
    }

}
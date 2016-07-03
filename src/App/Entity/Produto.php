<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ProdutoRepository")
 * @ORM\Table("produtos")
 */
class Produto  extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;
    
    /**
     * @ORM\Column(type="string", length=800)
     */
    private $descricao;
    
    /**
     * @ORM\Column(type="decimal")
     */
    private $valor;
    
    /**
    * @ORM\OneToOne(targetEntity="Categoria")
    * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
    */
    private $categoria;
    
    /**
    * @ORM\ManyToMany(targetEntity="Tag")
    * @ORM\JoinTable(name="produtos_tags",
    *      joinColumns={@ORM\JoinColumn(name="produto_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
    *   )
    */
    private $tags;
    
    /**
     * @ORM\Column(type="string")
     */
    private $imagem;

    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
        return $this;
    }
    
    public function __construct(array $data = array())
    {
        $this->tags = new ArrayCollection();
        parent::__construct($data);
    }
    
    function getId()
    {
        return $this->id;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getDescricao()
    {
        return $this->descricao;
    }

    function getValor()
    {
        return $this->valor;
    }

    function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }
    
    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
        return $this;
    }
    
    public function removeTags(Tag $tag)
    {
        $this->tags->remove($tag);
        return $this;
    }
    
    public function toArray()
    {
        return \get_object_vars($this);
    }
}

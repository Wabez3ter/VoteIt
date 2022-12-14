<?php

namespace App\VoteIt\Model\DataObject;

class Utilisateur extends AbstractDataObject
{
    private string $identifiant;
    private string $motDePasse;
    private string $nom;
    private string $prenom;
    private string $dateNaissance;
    private string $iconeLink;
    private string $mail;
    private string $mailAValider;
    private string $nonce;
    private string $grade;

    /**
     * @param String $identifiant
     * @param String $nom
     * @param String $prenom
     * @param String $dateNaissance
     * @param String $mail
     * @param String $iconeLink
     * @param String $grade
     */
    public function __construct(string $identifiant, string $motdepasse, string $nom, string $prenom, string $dateNaissance, string $iconeLink, string $mail, string $mailAValider, string $nonce, string $grade)
    {
        $this->identifiant = $identifiant;
        $this->motDePasse = $motdepasse;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->iconeLink = $iconeLink;
        $this->mail = $mail;
        $this->mailAValider = $mailAValider;
        $this->nonce = $nonce;
        $this->grade = $grade;
    }

    public function formatTableau(): array
    {
        {
            return array(
                "idUtilisateur" => $this->getIdentifiant(),
                "motDePasseUtilisateur" => $this->getMotDePasse(),
                "nomUtilisateur" => $this->getNom(),
                "prenomUtilisateur" => $this->getPrenom(),
                "dateNaissanceUtilisateur" => $this->getDateNaissance(),
                "iconeLink" => $this->getIconeLink(),
                "mailUtilisateur" => $this->getMail(),
                "mailAValider" => $this->getMailAValider(),
                "nonce" => $this->getNonce(),
                "gradeUtilisateur" => $this->getGrade(),
            );
        }
    }






    //GETTER & SETTER
    /**
     * @return String
     */
    public function getIdentifiant(): string
    {
        return $this->identifiant;
    }

    /**
     * @param String $identifiant
     */
    public function setIdentifiant(string $identifiant): void
    {
        $this->identifiant = $identifiant;
    }

    /**
     * @return String
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param String $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return String
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param String $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return String
     */
    public function getDateNaissance(): string
    {
        return $this->dateNaissance;
    }

    /**
     * @return String
     */
    public function getDateNaissanceFR(): string
    {
        return date_format(date_create($this->dateNaissance), 'd/m/Y');
    }

    /**
     * @param String $dateNaissance
     */
    public function setDateNaissance(string $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return String
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param String $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return String
     */
    public function getIconeLink(): string
    {
        return $this->iconeLink;
    }

    /**
     * @param String $iconeLink
     */
    public function setIconeLink(string $iconeLink): void
    {
        $this->iconeLink = $iconeLink;
    }

    /**
     * @return String
     */
    public function getGrade(): string
    {
        return $this->grade;
    }

    /**
     * @param String $grade
     */
    public function setGrade(string $grade): void
    {
        $this->grade = $grade;
    }

    /**
     * @return String
     */
    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    /**
     * @param String $motDePasse
     */
    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @return String
     */
    public function getMailAValider(): string
    {
        return $this->mailAValider;
    }

    /**
     * @param String $mailAValider
     */
    public function setMailAValider(string $mailAValider): void
    {
        $this->mailAValider = $mailAValider;
    }

    /**
     * @return String
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param String $nonce
     */
    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }


}

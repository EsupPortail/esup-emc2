-- Suppression de paramètres qui ne sont plus utilisés
delete from unicaen_parametre_parametre where code='TEMOIN_GRADE';
delete from unicaen_parametre_parametre where code='FILTRAGE_GRADE/CORPS/EMPLOI_TYPE';

-- Onglet "Entretien professionnel"
update unicaen_privilege_categorie set libelle='Entretiens professionnels' where code='ENTRETIEN_PROFESSIONNEL';

-- Paramètres de "blocage" (0+)
update unicaen_parametre_parametre
set
libelle='Blocage strict de la modification des entretiens professionnels',
ordre=1,
description='<p>Si le paramètre est à <i>true</i> alors la modification des entretiens professionnels (comptes-rendus) doit être faite durant l''ouverture de la campagne.</p>'
where
code='CAMPAGNE_BLOCAGE_STRICT_MODIFICATION';
update unicaen_parametre_parametre
set
libelle='Blocage strict de la validation des entretiens professionnels',
ordre=2,
description='<p>Si le paramètre est à <i>true</i> alors la validation des entretiens professionnels doit être faite durant l''ouverture de la campagne.</p>'
where
code='CAMPAGNE_BLOCAGE_STRICT_VALIDATION';
update unicaen_parametre_parametre
set
libelle='Blocage de l''affichage du CREP aux agents si validation du responsable non réalisée',
ordre=11,
description='<p>Si le paramètre est à <i>true</i> alors les agents ne peuvent pas visualiser le CREP avant la validation de leur responsable.</p>'
where
code='CREP_BLOCAGE_VISUALISATION_AGENT_AVANT_VALIDATION';
update unicaen_parametre_parametre
set
libelle='Blocage de l''affichage du CREF aux agents si validation du responsable non réalisée',
ordre=12,
description='<p>Si le paramètre est à <i>true</i> alors les agents ne peuvent pas visualiser le CREF avant la validation de leur responsable.</p>'
where
code='CREF_BLOCAGE_VISUALISATION_AGENT_AVANT_VALIDATION';

-- Paramètres liés aux notifications (100+)
update unicaen_parametre_parametre
set
libelle='Envoi de notification à l''ouverture de la campagne',
ordre=100,
description='<p>Si le paramètre est à <i>true</i> alors les responsables et les agent·es sont notifié·es lors de l''ouverture de la campagne.
    Le template pour les agents est <strong>CAMPAGNE_OUVERTURE_BIATSS</strong> et pour les directeurs <strong>CAMPAGNE_OUVERTURE_DAC</strong>.</p>'
where
code='CAMPAGNE_NOTIFIER_OUVERTURE';
update unicaen_parametre_parametre
set
libelle='Adresse électronique de la liste de diffusion vers les responsables de services et/ou structures',
ordre=110,
description='<p>Utilisée lors de l''ouverture d''une campagne d''entretien professionnel pour notifier les directeurs via le template <strong>CAMPAGNE_OUVERTURE_DAC</strong>.</p>'
where
code='MAIL_LISTE_DAC';
update unicaen_parametre_parametre
set
libelle='Adresse électronique de la liste de diffusion vers le personnel',
ordre=111,
description='<p>Utilisée lors de l''ouverture d''une campagne d''entretien professionnel pour notifier les agents via le template <strong>CAMPAGNE_OUVERTURE_BIATSS</strong>.</p>'
where
code='MAIL_LISTE_BIATS';

-- Paramètre lié aux délais (200+)
update unicaen_parametre_parametre
set
libelle='Délai minimal recommandé pour convoquer un·e agent·e (en jours)',
ordre=200,
description='<p>Si le délai n''est pas respecté, provoque un message d''avertissement au responsable de l''entretien professionnel.</p>'
where
code='DELAI_CONVOCATION_AGENT';
update unicaen_parametre_parametre
set
libelle='Délai d''acceptation de l''entretien par l''agent (en jours)',
ordre=201,
description='<p>Si le délai est dépassé, on avertit l''agent que son entretien professionnel peut être refusé.</p>'
where
code='DELAI_ACCEPTATION_AGENT';
update unicaen_parametre_parametre
set
libelle='Durée de la période d''observation des agent·es (en jours)',
ordre=210,
description='<p>Durée de la période durant laquelle les agent·es peuvent à l''issu de la validation du responsable d''entretien professionnel exprimer des observations.<br>La valeur prescrite est de 8 jours.</p>'
where
code='DELAI_OBSERVATION_AGENT';

-- Paramètres supplémentaires (300+)
update unicaen_parametre_parametre
set
libelle='Activation de l''observation finale par l''agent',
ordre=300,
description='<p>Pour désactiver cette observation basculer la valeur à <i>false</i>.</p>'
where
code='OBSERVATION_AGENT_FINAL';

update unicaen_parametre_parametre
set
libelle='Lien vers les documents associés à l''entretien professionnel',
ordre=310,
description='<p>Lien présenté sur la page des entretiens professionnels vers les documents d''aide. <br> Si laissé vide alors le bloc d''aide est masqué.</p>'
where
code='INTRANET_DOCUMENT';

-- Paramètres liés à l'exclusion (1000+)
update unicaen_parametre_parametre
set
libelle='[EXCLUSION] Temoin des affectations considérées',
ordre=1000,
description='<p>principale / hiérarchique / fonctionnelle<br><br>Exemples : <ul><li><code>principale;hierarchique</code> : exclut les agents ayant une affectation principale et ceux qui ont une affectation hiérarchique.</li></ul></p>'
where
code='TEMOIN_AFFECTATION_EXCLUS';
update unicaen_parametre_parametre
set
libelle='[EXCLUSION] Temoin des corps considérés',
ordre=1100,
description='<p>À retrouver dans Ressources > Corps > Libellé court (disponible au survol du libellé) ou dans la table carriere_corps la colonne lib_court<br><br>
    Exemples : <ul>
    <li><code>mait.conf.;prof.univ</code> : exclut les maîtres de conférence ainsi que les professeurs des universités</li>
    <li><code>mait.conf.&prof.univ</code> : exclut les maitres de conférence également professeurs des universités</li>
    <li><code>!prof.univ</code> : exclut ceux qui ne sont PAS professeur des universités</li>
</ul>'
where
code='TEMOIN_CORPS_EXCLUS';
update unicaen_parametre_parametre
set
libelle='[EXCLUSION] Emploi-Type considérés',
ordre=1200,
description='<p>À retrouver dans Ressources > Emploi Types > Code<br><br>
    Exemples : <ul>
    <li><code>VACAD;POSTDOC</code> : exclut de la campagne les agents ayant l''emploi-type vacataires administratifs ainsi que ceux ayant l''emploi type Post Doctorants</li>
    <li><code>postdoc&cnu er</code> : exclut les Post Doctorants également enseignants chercheurs</li>
    <li><code>!postdoc</code> : exclut ceux qui ne sont PAS Post Doctorants</li>
</ul>'
where
code='TEMOIN_EMPLOITYPE_EXCLUS';
update unicaen_parametre_parametre
set
libelle='[EXCLUSION] Témoin des statuts considérées',
ordre=1300,
description='<p>cdi / cdd / titulaire / vacataire / enseignant / administratif / chercheur / doctorant / detacheIn / detacheOut / dispo / longue_maladie / conge_parental / postdoc<br><br>
    Exemples : <ul>
    <li><code>longue_maladie</code> : exclut les agents en longue maladie</li>
    <li><code>VACAD;POSTDOC</code> : exclut les vacataires administratifs ainsi que les Post Doctorants</li>
    <li><code>enseignant&chercheur</code> : exclut les enseignants chercheurs</li>
    <li><code>!postdoc</code> : exclut ceux qui ne sont PAS Post Doctorants</li>
</ul>'
where
code='TEMOIN_STATUT_EXCLUS';

-- Paramètres liés au filtrage (2000+)
update unicaen_parametre_parametre
set
libelle='[FILTRAGE] Temoin des affectations considérées',
ordre=2000,
description='<p>principale / hiérarchique / fonctionnelle<br><br>Exemples : <ul><li><code>principale;hierarchique</code> : classe sans obligation les agents ayant une affectation principale et ceux qui ont une affectation hiérarchique.</li></ul></p>'
where
code='TEMOIN_AFFECTATION';
update unicaen_parametre_parametre
set
libelle='[FILTRAGE] Temoin des corps considérés',
ordre=2100,
description='<p>À retrouver dans Ressources > Corps > Libellé court (disponible au survol du libellé) ou dans la table carriere_corps la colonne lib_court<br><br>
    Exemples : <ul>
    <li><code>mait.conf.;prof.univ</code> : classe sans obligation les maîtres de conférence ainsi que les professeurs des universités</li>
    <li><code>mait.conf.&prof.univ</code> : classe sans obligation les maitres de conférence également professeurs des universités</li>
    <li><code>!prof.univ</code> : classe sans obligation ceux qui ne sont PAS professeur des universités</li>
</ul>'
where
code='TEMOIN_CORPS';
update unicaen_parametre_parametre
set
libelle='[FILTRAGE] Emploi-Type considérés',
ordre=2200,
description='<p>À retrouver dans Ressources > Emploi Types > Code<br><br>
    Exemples : <ul>
    <li><code>VACAD;POSTDOC</code> : classe sans obligation pour la campagne les agents ayant l''emploi-type vacataires administratifs ainsi que ceux ayant l''emploi type Post Doctorants</li>
    <li><code>postdoc&cnu er</code> : classe sans obligation les Post Doctorants également enseignants chercheurs</li>
    <li><code>!postdoc</code> : classe sans obligation ceux qui ne sont PAS Post Doctorants</li>
</ul>'
where
code='TEMOIN_EMPLOITYPE';
update unicaen_parametre_parametre
set
libelle='[Filtrage] Témoin des statuts considérées',
ordre=2300,
description='<p>cdi / cdd / titulaire / vacataire / enseignant / administratif / chercheur / doctorant / detacheIn / detacheOut / dispo / longue_maladie / conge_parental / postdoc<br><br>
    Exemples : <ul>
    <li><code>longue_maladie</code> : classe sans obligation les agents en longue maladie</li>
    <li><code>VACAD;POSTDOC</code> : classe sans obligation les vacataires administratifs ainsi que les Post Doctorants</li>
    <li><code>enseignant&chercheur</code> : classe sans obligation les enseignants chercheurs</li>
    <li><code>!postdoc</code> : classe sans obligation ceux qui ne sont PAS Post Doctorants</li>
</ul>'
where
code='TEMOIN_STATUT';
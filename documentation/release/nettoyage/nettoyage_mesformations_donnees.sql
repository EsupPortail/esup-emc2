-- NETTOYAGE DES TRACES LIES A MES FORMATIONS


-- Nettoyage des privilèges
delete from unicaen_privilege_privilege where categorie_id in (select id from unicaen_privilege_categorie where namespace = 'Formation\Provider\Privilege');
delete from unicaen_privilege_categorie where namespace = 'Formation\Provider\Privilege';
delete from unicaen_privilege_privilege where categorie_id in (select id from unicaen_privilege_categorie where namespace = 'UnicaenEnquete\Provider\Privilege');
delete from unicaen_privilege_categorie where namespace = 'UnicaenEnquete\Provider\Privilege';

-- Nettoyage des états liés à Mes Formations
delete from unicaen_etat_instance where type_id in (select id from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='DEMANDE_EXTERNE'));
delete from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='DEMANDE_EXTERNE');
delete from unicaen_etat_categorie where code='DEMANDE_EXTERNE';
delete from unicaen_etat_instance where type_id in (select id from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='FORMATION_SESSION'));
delete from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='FORMATION_SESSION');
delete from unicaen_etat_categorie where code='FORMATION_SESSION';
delete from unicaen_etat_instance where type_id in (select id from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='FORMATION_INSCRIPTION'));
delete from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='FORMATION_INSCRIPTION');
delete from unicaen_etat_categorie where code='FORMATION_INSCRIPTION';
delete from unicaen_etat_instance where type_id in (select id from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='MES_FORMATIONS'));
delete from unicaen_etat_type where categorie_id = (select id from unicaen_etat_categorie where code='MES_FORMATIONS');
delete from unicaen_etat_categorie where code='MES_FORMATIONS';

-- Nettoyage des templates liées à Mes Formations
delete from unicaen_renderer_template where namespace = 'Formation\Provider\Template';
delete from unicaen_renderer_template where namespace = 'Formation\Provider\Privilege';

-- Nettoyage des validations
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'AGENT_FORMATION');
delete from unicaen_validation_type where code = 'AGENT_FORMATION';
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'FORMATION_DEMANDE_AGENT');
delete from unicaen_validation_type where code = 'FORMATION_DEMANDE_AGENT';
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'FORMATION_CHARTE_SIGNEE');
delete from unicaen_validation_type where code = 'FORMATION_CHARTE_SIGNEE';
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'FORMATION_DEMANDE_RESPONSABLE');
delete from unicaen_validation_type where code = 'FORMATION_DEMANDE_RESPONSABLE';
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'FORMATION_DEMANDE_DRH');
delete from unicaen_validation_type where code = 'FORMATION_DEMANDE_DRH';
delete from unicaen_validation_instance where type_id = (select id from unicaen_validation_type where code = 'FORMATION_DEMANDE_REFUS');
delete from unicaen_validation_type where code = 'FORMATION_DEMANDE_REFUS';

-- Nettoyage des evenements
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'rappel_formation_agent_avant');
delete from unicaen_evenement_type where code = 'rappel_formation_agent_avant';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'notification_formation_ouverte');
delete from unicaen_evenement_type where code = 'notification_formation_ouverte';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'notification_nouvelle_session');
delete from unicaen_evenement_type where code = 'notification_nouvelle_session';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'notification_rappel_session_imminente');
delete from unicaen_evenement_type where code = 'notification_rappel_session_imminente';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'cloture_automatique_inscription');
delete from unicaen_evenement_type where code = 'cloture_automatique_inscription';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'convocation_automatique');
delete from unicaen_evenement_type where code = 'convocation_automatique';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'formation_demande_retour');
delete from unicaen_evenement_type where code = 'formation_demande_retour';
delete from unicaen_evenement_instance where type_id = (select id from unicaen_evenement_type where code = 'formation_session_cloture');
delete from unicaen_evenement_type where code = 'formation_session_cloture';

-- Nettoyage des parametres
delete from unicaen_parametre_parametre where categorie_id = (select id from unicaen_parametre_categorie where code='FORMATION');
delete from unicaen_parametre_categorie where code = 'FORMATION';



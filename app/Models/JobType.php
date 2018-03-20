<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    static $list = [
        "Activities Worker",
        "Accommodation Warden",
        "Activity Coordinator",
        "Administrator",
        "Adult Guidance Worker",
        "Advice Worker",
        "Advocacy Worker",
        "Alexander Technique Teacher",
        "Ambulance Care Assistant",
        "Anaesthetist",
        "Area / Regional Manager",
        "Art Therapist",
        "Audiologist",
        "Bank Carer / Care Assistant",
        "Bank Nurse",
        "British Sign Language Interpreter",
        "Care Escort",
        "Care Home Advocate",
        "Care Home Manager",
        "Care Manager",
        "Care Worker",
        "Careers Adviser",
        "Carer",
        "Care Assistant",
        "Care Support Worker",
        "Child Psychotherapist",
        "Childminder",
        "Children's Nurse",
        "Clinical Lead",
        "Nurse Team Leader",
        "Clinical Psychologist",
        "Clinical Scientist",
        "Cognitive Behavioural Therapist",
        "Communication Support Worker",
        "Community Arts Worker",
        "Community Development Worker",
        "Community Education Officer",
        "Community Matron",
        "Community Transport Driver",
        "Community Transport Operations Manager",
        "Head Chef",
        "Chef",
        "Counselling Psychologist",
        "Counsellor",
        "Dental Hygienist",
        "Dental Nurse",
        "Dental Therapist",
        "Dentist",
        "Deputy Manager / Assistant Manager",
        "Dietitian",
        "Director / Divisional Director",
        "District Nurse",
        "Domestic",
        "Domestic Assistant",
        "Dramatherapist",
        "Driver",
        "Drug And Alcohol Worker",
        "Education Welfare Officer",
        "Educational Psychologist",
        "Emergency Care Assistant",
        "Emergency Medical Dispatcher",
        "Equality And Diversity Officer",
        "Family Mediator",
        "Family Support Worker",
        "Finance / Accountant / Bookkeeper",
        "Forensic Psychologist",
        "Foster Carer",
        "Gardener",
        "Geneticist",
        "Gp",
        "Grants Officer",
        "Health Play Specialist",
        "Health Promotion Specialist",
        "Health Service Manager",
        "Health Trainer",
        "Health Visitor",
        "Healthcare Assistant",
        "Healthcare Science Assistant",
        "High Intensity Therapist",
        "Horticultural Therapist",
        "Hospital Doctor",
        "Hospital Porter",
        "Housekeeper / Cleaner",
        "Housing Officer",
        "Housing Policy Officer",
        "Human Resources / Hr / Recruitment",
        "Kitchen Assistant / Catering Assistant",
        "Kitchen Manager",
        "Laundry Assistant",
        "Learning Disability Nurse",
        "Learning Mentor",
        "Life Coach",
        "Maintenance / Handyperson",
        "Marketing",
        "Maternity Support Worker",
        "Mental Health Nurse",
        "Microbiologist",
        "Midwife",
        "Money Adviser",
        "Music Therapist",
        "Nurse",
        "Nursery Manager",
        "Nursery Worker",
        "Nutritionist",
        "Occupational Health Nurse",
        "Occupational Therapist",
        "Occupational Therapy Support Worker",
        "Operating Department Practitioner",
        "Orthoptist",
        "Palliative Care Assistant",
        "Paramedic",
        "Pathologist",
        "Patient Advice And Liaison Service Officer",
        "Patient Transport Service Controller",
        "Personal Assistant",
        "Pharmacist",
        "Pharmacologist",
        "Pharmacy Assistant",
        "Pharmacy Technician",
        "Phlebotomist",
        "Physiotherapist",
        "Physiotherapy Assistant",
        "Play Therapist",
        "Playworker",
        "Podiatrist",
        "Podiatry Assistant",
        "Practice Nurse",
        "Primary Care Graduate Mental Health Worker",
        "Probation Officer",
        "Probation Services Officer",
        "Prosthetist-Orthotist",
        "Psychiatrist",
        "Psychologist",
        "Psychotherapist",
        "Radiographer",
        "Radiography Assistant",
        "Receptionist",
        "Registered Manager / Service Manager",
        "Rehabilitation Worker",
        "Religious Leader",
        "Residential Support Worker",
        "School Matron",
        "School Nurse",
        "Senior Care Worker",
        "Senior Carer / Head Of Care / Team Leader",
        "Sexual Health Adviser",
        "Social Services Manager",
        "Social Work Assistant",
        "Social Worker",
        "Speech And Language Therapist",
        "Speech And Language Therapy Assistant",
        "Sports Physiotherapist",
        "Staff Nurse",
        "Sterile Services Technician",
        "Substance Misuse Outreach Worker",
        "Surgeon",
        "Training & Development",
        "Victim Care Officer",
        "Volunteer Organiser",
        "Welfare Rights Officer",
        "Youth Offending Team Officer",
        "Youth Worker",
    ];

    protected $fillable = ['name'];

    public function profile()
    {
        return $this->belongsToMany('App\Models\Profile');
    }
}
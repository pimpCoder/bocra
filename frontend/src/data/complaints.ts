export type ComplaintRight = {
  title: string
  description: string
}

export type ComplaintStep = {
  title: string
  description: string
}

export type ComplaintSection = {
  id: string
  heading: string
  intro: string[]
  subheading?: string
  rights?: ComplaintRight[]
  steps?: ComplaintStep[]
  notes?: string[]
  listTitle?: string
  listItems?: string[]
  contactTitle?: string
  contactLines?: string[]
  formComplaintTypes?: string[]
}

export const complaintSections: ComplaintSection[] = [
  {
    id: 'consumer-education-and-advice',
    heading: 'Consumer Education And Advice',
    intro: [
      'The Botswana Communications Regulatory Authority is empowered by the Communications Regulatory Authority Act 2012, to promote the interests of consumers, purchasers and other users of the telecommunication services in respect of price, quality and variety of, such services and equipment supplied for provision of the same. Liberalisation of the telecommunications market brought with it variety of telecommunications services that differ in price and quality. The growth of the telecommunications industry is rapid. New telecommunications service and technology emerge fast. Consumers need to obtain sufficient information about these services in order to make informed choices and get the best value for money, as their basic rights. Consumer Rights The Constitution of Botswana provides for rights, which are recognised as inalienable to every citizen of this country.',
      'The BOCRA has also listed some rights which every consumer of telecommunications services is entitled to, irrespective of his or her status in life. It is therefore incumbent upon the consumer to demand these rights that include the following.',
    ],
    rights: [
      {
        title: 'The Right To Be Informed',
        description:
          "This right impels service providers to factually and comprehensively inform consumers about products or services devoid of falsehood, deceit, misleading information and advertisement. It is as such the responsibility of service providers to always give accurate, sufficient and relevant information to guide consumers in making rationale choices and informed decisions. It amounts to a breach of consumers' rights not to disclose all information pertaining to a product and service.",
      },
      {
        title: 'The Right To Choice',
        description:
          'This has to do with assurance of access to a variety of products and services at competitive prices so that options of which product to buy and which not, will exist for the different segments of society.',
      },
      {
        title: 'The Right To Be Heard',
        description:
          'This provides ample opportunities and channels of expressing grievances, opinions, lodging complaints, suggesting ways and means of improving service delivery to customers. Customer is always right and it is therefore incumbent upon all providers of telecommunications services to respect and uphold the right of the customer.',
      },
      {
        title: 'The Right To Safety',
        description:
          'This is aimed at protecting consumers against marketing unwholesome, sub-standard, defective goods and services.',
      },
    ],
    notes: [
      'Should you feel that any of these rights have not been respected, you need to take it up with the service provider and ultimately the Communications Regulatory Authority.',
    ],
  },
  {
    id: 'registering-complaints',
    heading: 'Registering Complaints',
    subheading: 'Complaint Handling Procedures',
    intro: [
      'BOCRA continues to monitor the quality of service provided by licensees. To this end, the BOCRA has developed Quality of Service Guidelines for operators with a view to improve and maintain service quality by identifying service deficiencies, specifying network service quality parameters, improving operations, performance and networks. In the event that you are not satisfied with the service provided by your service provider, you may wish to lodge a complaint against the concerned service provider. You should approach your service provider first for assistance.',
      'BOCRA will investigate a consumer complaint against a service provider if there is sufficient evidence to establish a prima facie case on possible breaches of any provisions under the Communications Regulatory Authority Act 2012 or any conditions under the operators licence.',
      'Customers are advised to obtain and familiarise themselves with complaints handling processes of the respective service providers.',
    ],
    steps: [
      {
        title: 'Step 1: Address The Complaint To The Service Provider',
        description:
          'Consumers will first address their complaints to the appropriate service provider. Consumers should first explore and exhaust all possible channels of remedy available within the operator(s) before any reference to the BTA.',
      },
      {
        title: 'Step 2 : Ask The Service Provider For The Time It Will Take To Resolve The Complaint',
        description:
          'Consumers should ask the operator(s) to state the period within which complaints will be resolved. Complaints to an operator will be resolved within the time frame as stipulated by the service provider. Any deviation should be accompanied by a written explanation to the complainant.',
      },
      {
        title: 'Step 3 : Keep Copies Of Correspondence Of The Complaint',
        description:
          'It is important that complainants keep records of all correspondence between themselves and the operators. Where possible, complainants should request service providers to acknowledge receipt by stamping their copies of complaint letter.',
      },
      {
        title: 'Step 4 : Escalate Complaint To The Highest Level Within The Service Provider',
        description:
          'If a complaint is not resolved in the first instance, the consumer should request for the complaint to be escalated to a higher level in line with the Operators Guidelines for Handling Complaints.',
      },
      {
        title: 'Step 5 : Escalate The Complaint To The BOCRA',
        description:
          'Where the operator has not satisfactorily resolved a complaint, the consumer should refer that complaint to the BOCRA.',
      },
    ],
    listTitle: 'The Notification about the referred complaint shall include the following:',
    listItems: [
      'The names and addresses of the parties involved',
      'A brief statement of facts on the complaint',
      'Copies of any relevant supporting documents',
      'The relief or remedy sought',
    ],
    contactTitle:
      'Complaints may be brought to the BOCRA by post, hand delivery, email or fax to the following address:',
    contactLines: [
      'The Chief Executive',
      'Botswana Communications Regulatory Authority',
      'Plot No. 206/207, Independence Avenue',
      'Private Bag 00495',
      'Gaborone',
      'Tel: +267 395 7755',
      'Fax: +267 395 7976',
      'Email: info@bta.org.bw',
      'Website: www.bta.org.bw',
    ],
  },
  {
    id: 'file-a-complaint',
    heading: 'File a Complaint',
    intro: ['Please complete the complaint form below and submit your details.'],
    formComplaintTypes: [
      'Research',
      'Licencing',
      'Policy and Regulation',
      'Standards',
      'Numbering',
      'ccTLD',
      'Radio Interference',
      'Billing',
      'Internet Speed',
      'Quality of Service',
      'Broadcasting',
    ],
  },
]

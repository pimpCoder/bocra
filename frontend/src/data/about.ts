export type AboutIconName =
  | 'shield'
  | 'book'
  | 'users'
  | 'briefcase'
  | 'globe'
  | 'layers'

export type AboutSection = {
  id: string
  heading: string
  summary: string
  bullets: string[]
  icon: AboutIconName
}

export const aboutSections: AboutSection[] = [
  {
    id: 'profile',
    heading: 'Profile',
    summary:
      'Botswana Communications Regulatory Authority (BOCRA) was established through the Communications Regulatory Authority Act, 2012, and became operational on April 1, 2013, as a converged regulator for telecommunications, internet and ICTs, radio communications, broadcasting, postal services, and related matters.',
    bullets: [
      'Mission: Regulate the communications sector for the promotion of competition, innovation, consumer protection, and universal access.',
      'Vision: A connected and digitally driven society.',
      'Values: Excellence, Proactiveness, Integrity, and People.',
      'Core business: Lead communications sector advancement to expand enhanced services, economic opportunities, employment, and sustainable growth.',
      'Strategic pillars: Competition; Universal Access and Service; Consumer Protection; Resource Optimisation; Talent Management; Stakeholder Engagement.',
    ],
    icon: 'shield',
  },
  {
    id: 'ceo-message',
    heading: 'A Word From The Chief Executive',
    summary:
      'I have the pleasure to present to you, our valued stakeholder, the new and improved BOCRA website. The improved website marks our continuous efforts to better reach out to you and at the same time receive your feedback so that we can serve you better. The website carries a new look with improved colour scheme and technical capabilities to improve navigation and enhance your user experience. The website can now be accessed through a variety of devices including hand held devices like mobile handsets without a compromise on its functionality. It is also designed to link better with our other online platforms particularly social media platforms.\n\nI invite you to explore and provide feedback on your user experience. Your insights are crucial in helping us improve this platform in a way that meets your expectations.\nMartin Mokgware\nChief Executive',
    bullets: [],
    icon: 'briefcase',
  },
  {
    id: 'history',
    heading: 'History of Communication Regulation',
    summary:
      'Botswana\'s communications regulatory journey evolved from early telecom liberalisation into a converged framework under BOCRA, with key reforms in competition, licensing, pricing, spectrum, and consumer-focused access.',
    bullets: [
      '1996-1999: Telecommunications Act approved; BTA established; mobile market competition introduced; first FM radio and ISP licenses issued.',
      '2000-2006: Numbering reforms, interconnection rulings, market liberalisation and pricing studies, and spectrum/frequency management improvements.',
      '2007-2011: Service-neutral licensing, spectrum strategy and monitoring facility, SIM registration, and tariff reductions informed by cost-model directives.',
      '2012-2014: CRA Act passed (2012); BOCRA established (2013); BTC structural separation into BTCL and BoFiNet; UASF created (2014).',
      '2015-2016: Regional roaming tariff reductions, digital migration specifications, major regional forums hosted, and rollout of new ICT and postal licensing.',
    ],
    icon: 'book',
  },
  {
    id: 'organogram',
    heading: 'Organogram',
    summary:
      'BOCRA is organized into director-led departments that support regulatory execution, sector development, technical oversight, and stakeholder communication.',
    bullets: [
      'Departments: Compliance and Monitoring; Corporate Support; Business Development; Technical Services; Corporate Communications and Relations.',
      'Objectives: Facilitate entry of new service providers and improve efficiency in frequency spectrum management.',
      'Objectives: Support universal service financing and special tariffs for disadvantaged users in line with government policy.',
      'Objectives: Promote user representation, implement consumer protection measures, and build a transparent, high-performing institution.',
      'Public actions: Apply for a license and file a complaint through BOCRA service channels.',
    ],
    icon: 'layers',
  },
  {
    id: 'board-of-directors',
    heading: 'Board of Directors',
    summary:
      'BOCRA is led by seven non-executive board members and one ex-officio member (the Chief Executive), appointed by the Minister responsible for Communications under Section 4 of the Communications Regulatory Authority Act, 2012. The current Board took effect on August 1, 2025.',
    bullets: [
      'Strategic direction is aligned to the Botswana National Digital Economy Roadmap 2025-2030 and the Botswana Economic Transformation Programme.',
      'Dr. Bokamoso Basutli, PhD - Chairperson; Professional Engineer and academic leader in communications systems, signal processing, and AI.',
      'Mr. Moabi Pusumane - Vice Chairperson; commercial executive with broad leadership across telecom and market strategy.',
      'Board Members: Ms. Montle Phuthego; Ms. Alta Dimpho Seleka; Ms. Lebogang George; Mr. Ronald Kgafela, CODP; Dr. Kennedy Ramojela.',
      'Board composition combines expertise in engineering, economics, finance, law, human capital, media, governance, and digital transformation.',
    ],
    icon: 'users',
  },
  {
    id: 'executive-management',
    heading: 'Executive Management',
    summary:
      'BOCRA\'s executive management team leads implementation of strategy, operations, licensing, compliance, finance, legal governance, broadband development, and stakeholder service delivery.',
    bullets: [
      'Mr. Martin Mokgware - Chief Executive.',
      'Mr. Murphy Setshwane - Director Business Development; Mr. Peter Tladinyane - Director Corporate Services; Ms. Bonny Mine - Director Finance.',
      'Mr. Bathopi Luke - Director Technical Services; Ms. Tebogo Mmoshe - Director of Licensing.',
      'Ms. Maitseo Ratladi - Director Broadband and Universal Service; Ms. Joyce Isa-Molwane - Director Legal, Compliance & Board Secretary.',
    ],
    icon: 'globe',
  },
]

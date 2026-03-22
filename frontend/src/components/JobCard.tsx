import { FiBriefcase, FiMapPin } from 'react-icons/fi'
import styles from './JobCard.module.css'

type JobTagInfo = {
  location: string
  type: string
}

type JobCardProps = {
  category: string
  title: string
  description: string
  tags: JobTagInfo
}

function JobCard({ category, title, description, tags }: JobCardProps) {
  return (
    <article className={styles.card}>
      <p className={styles.category}>{category}</p>
      <h3 className={styles.title}>{title}</h3>
      <p className={styles.description}>{description}</p>

      <div className={styles.metaRow}>
        <span className={styles.metaItem}>
          <FiMapPin aria-hidden="true" className={styles.icon} />
          {tags.location}
        </span>
        <span className={styles.metaItem}>
          <FiBriefcase aria-hidden="true" className={styles.icon} />
          {tags.type}
        </span>
      </div>
    </article>
  )
}

export default JobCard
